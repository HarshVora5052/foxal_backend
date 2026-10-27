<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public static function clearCache(): void
    {
        Cache::increment('api_products_version');
    }

    public function index(Request $request): JsonResponse
    {
        $version = Cache::get('api_products_version', 1);
        $cacheKey = 'api_products_v' . $version . '_' . md5($request->fullUrl());

        $responseData = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request) {
            $query = Product::with('category')
                ->where('is_active', true);

            // Search by keyword
            if ($q = $request->input('q')) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('fragrance', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            }

            // Filter by category ID or slug
            if ($categoryId = $request->input('category_id')) {
                $query->where('category_id', $categoryId);
            } elseif ($categorySlug = $request->input('category')) {
                $query->whereHas('category', function ($sub) use ($categorySlug) {
                    $sub->where('slug', $categorySlug);
                });
            }

            // Filter by fragrance family
            if ($fragrance = $request->input('fragrance')) {
                $query->where('fragrance', 'like', "%{$fragrance}%");
            }

            // Filter by price range
            if ($minPrice = $request->input('min_price')) {
                $query->where('price', '>=', (float) $minPrice);
            }
            if ($maxPrice = $request->input('max_price')) {
                $query->where('price', '<=', (float) $maxPrice);
            }

            // Featured only
            if ($request->boolean('featured')) {
                $query->where('is_featured', true);
            }

            // Sorting
            $sort = $request->input('sort', 'latest');
            match ($sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'rating' => $query->orderBy('avg_rating', 'desc'),
                'name_asc' => $query->orderBy('name', 'asc'),
                default => $query->latest(),
            };

            $perPage = min((int) $request->input('per_page', 12), 50);
            $products = $query->paginate($perPage);

            $encryptedPayload = \Illuminate\Support\Facades\Crypt::encryptString(json_encode($products->items()));

            return [
                'success' => true,
                'message' => 'Products fetched successfully.',
                'data' => $products->items(),
                'encrypted_payload' => $encryptedPayload,
                'encryption' => 'AES-256-CBC',
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ];
        });

        return response()->json($responseData)
            ->header('Cache-Control', 'public, max-age=60, s-maxage=120, stale-while-revalidate=300');
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::with([
            'category',
            'variants' => fn($q) => $q->where('is_active', true),
            'approvedReviews.user:id,name',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully.',
            'data' => $product,
        ]);
    }

    public function reviews(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $reviews = $product->approvedReviews()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Product reviews retrieved successfully.',
            'data' => $reviews->items(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    public function storeReview(Request $request, string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $user = $request->user();

        // Check if user has already reviewed
        $existing = Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a review for this product.',
                'data' => null,
            ], 422);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
            'is_approved' => false, // Needs admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully. It will be published after moderation.',
            'data' => $review,
        ], 201);
    }

    /**
     * All-in-one encrypted product feed for the frontend.
     * Contains Title, Description, Original Price (MRP), Discount Price,
     * Stock, Fragrance, Thumbnail, Gallery, and AES-256 Encrypted Payload.
     */
    public function feed(): JsonResponse
    {
        $version = Cache::get('api_products_version', 1);
        $cacheKey = 'api_feed_v' . $version;

        $responseData = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $categories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
            $products = Product::with('category')
                ->where('is_active', true)
                ->latest()
                ->get();

            $formattedProducts = $products->map(function ($product) {
                $images = is_array($product->images) ? $product->images : [];
                $thumbnail = $images[0] ?? null;
                $gallery = array_slice($images, 1);
                $mrp = (float) $product->mrp;
                $price = (float) $product->price;
                $discountAmount = max(0, $mrp - $price);
                $discountPercent = $mrp > 0 ? round(($discountAmount / $mrp) * 100) : 0;

                return [
                    'id' => $product->id,
                    'title' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'original_price' => $mrp,
                    'discount_price' => $price,
                    'discount_percentage' => $discountPercent,
                    'savings' => $discountAmount,
                    'currency' => 'INR',
                    'currency_symbol' => '₹',
                    'stock' => $product->stock,
                    'in_stock' => $product->stock > 0,
                    'fragrance' => $product->fragrance,
                    'brand' => $product->brand,
                    'category' => [
                        'id' => $product->category?->id,
                        'name' => $product->category?->name,
                        'slug' => $product->category?->slug,
                    ],
                    'section' => $product->category?->name,
                    'thumbnail' => $thumbnail,
                    'gallery' => $gallery,
                    'all_images' => $images,
                    'avg_rating' => (float) $product->avg_rating,
                    'is_featured' => (bool) $product->is_featured,
                ];
            });

            // Group by requested sections: Incense Cones & Incense Sticks
            $sections = [
                'incense_cones' => $formattedProducts->where('category.slug', 'incense-cones')->values(),
                'incense_sticks' => $formattedProducts->where('category.slug', 'incense-sticks')->values(),
            ];

            $payload = [
                'sections' => $sections,
                'products' => $formattedProducts->values(),
                'categories' => $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug]),
                'generated_at' => now()->toIso8601String(),
            ];

            // Encrypt payload using AES-256 (Laravel Crypt)
            $encryptedPayload = \Illuminate\Support\Facades\Crypt::encryptString(json_encode($payload));

            return [
                'success' => true,
                'message' => 'All-in-one product feed retrieved.',
                'data' => [
                    'products' => $formattedProducts->values(),
                    'sections' => $sections,
                    'total_products' => $formattedProducts->count(),
                    'encrypted_payload' => $encryptedPayload,
                    'encryption' => 'AES-256-CBC',
                ],
            ];
        });

        return response()->json($responseData)
            ->header('Cache-Control', 'public, max-age=60, s-maxage=120, stale-while-revalidate=300');
    }

    /**
     * Decrypt an encrypted feed payload on demand
     */
    public function decryptFeed(Request $request): JsonResponse
    {
        $payload = $request->input('payload');
        if (!$payload) {
            return response()->json([
                'success' => false,
                'message' => 'Payload is required.',
            ], 422);
        }

        try {
            $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($payload);
            return response()->json([
                'success' => true,
                'data' => json_decode($decrypted, true),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to decrypt payload: ' . $e->getMessage(),
            ], 400);
        }
    }
}
