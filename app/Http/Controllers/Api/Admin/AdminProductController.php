<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ProductController;
use App\Models\AuditLog;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')->withTrashed();

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('fragrance', 'like', "%{$q}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->boolean('low_stock')) {
            $query->where('stock', '<', 10);
        }

        $products = $query->latest()->paginate((int) $request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Admin products retrieved.',
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'fragrance' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'mrp' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'unique:products,sku'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'string'],
            'image_files' => ['nullable', 'array', 'max:5'],
            'image_files.*' => ['file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB max
        ]);

        $images = $validated['images'] ?? [];

        // Handle file uploads to local public/uploads/products
        if ($request->hasFile('image_files')) {
            $destPath = public_path('uploads/products');
            if (!file_exists($destPath)) {
                @mkdir($destPath, 0755, true);
            }
            $frontendPath = base_path('../foxal_frontend/public/uploads/products');
            if (file_exists(base_path('../foxal_frontend/public')) && !file_exists($frontendPath)) {
                @mkdir($frontendPath, 0755, true);
            }

            foreach ($request->file('image_files') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $filename = time() . '_' . Str::random(10) . '.' . $ext;
                $file->move($destPath, $filename);
                if (file_exists(base_path('../foxal_frontend/public'))) {
                    @copy($destPath . '/' . $filename, $frontendPath . '/' . $filename);
                }
                $images[] = '/uploads/products/' . $filename;
            }
        }

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        // Ensure slug uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-" . $counter++;
        }

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'fragrance' => $validated['fragrance'] ?? null,
            'brand' => $validated['brand'] ?? 'Foxal',
            'mrp' => $validated['mrp'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'sku' => $validated['sku'] ?? ('FX-' . strtoupper(Str::random(6))),
            'images' => $images,
            'is_active' => $validated['is_active'] ?? true,
            'is_featured' => $validated['is_featured'] ?? false,
        ]);

        AuditLog::log('product.created', $product, null, $product->toArray());
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product->load('category'),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::withTrashed()->with(['category', 'variants'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved.',
            'data' => $product,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $old = $product->toArray();

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'unique:products,slug,' . $id],
            'description' => ['nullable', 'string'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'fragrance' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'mrp' => ['sometimes', 'required', 'numeric', 'min:0'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'sku' => ['sometimes', 'nullable', 'string', 'unique:products,sku,' . $id],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'string'],
            'image_files' => ['nullable', 'array', 'max:5'],
            'image_files.*' => ['file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ]);

        $images = $validated['images'] ?? $product->images ?? [];

        if ($request->hasFile('image_files')) {
            $destPath = public_path('uploads/products');
            if (!file_exists($destPath)) {
                @mkdir($destPath, 0755, true);
            }
            $frontendPath = base_path('../foxal_frontend/public/uploads/products');
            if (file_exists(base_path('../foxal_frontend/public')) && !file_exists($frontendPath)) {
                @mkdir($frontendPath, 0755, true);
            }

            foreach ($request->file('image_files') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $filename = time() . '_' . Str::random(10) . '.' . $ext;
                $file->move($destPath, $filename);
                if (file_exists(base_path('../foxal_frontend/public'))) {
                    @copy($destPath . '/' . $filename, $frontendPath . '/' . $filename);
                }
                $images[] = '/uploads/products/' . $filename;
            }
        }
        $validated['images'] = $images;

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        AuditLog::log('product.updated', $product, $old, $product->fresh()->toArray());
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $product->load('category'),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $old = $product->toArray();
        $product->delete(); // Soft delete

        AuditLog::log('product.soft_deleted', $product, $old, null);
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Product soft-deleted successfully.',
            'data' => null,
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        AuditLog::log('product.restored', $product, null, $product->toArray());
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Product restored successfully.',
            'data' => $product,
        ]);
    }

    public function toggleActive(int $id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $old = ['is_active' => $product->is_active];
        $product->update(['is_active' => !$product->is_active]);

        AuditLog::log('product.toggle_active', $product, $old, ['is_active' => $product->is_active]);
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Product active status updated.',
            'data' => ['is_active' => $product->is_active],
        ]);
    }

    public function toggleFeatured(int $id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $old = ['is_featured' => $product->is_featured];
        $product->update(['is_featured' => !$product->is_featured]);

        AuditLog::log('product.toggle_featured', $product, $old, ['is_featured' => $product->is_featured]);
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Product featured status updated.',
            'data' => ['is_featured' => $product->is_featured],
        ]);
    }

    public function updateStock(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product = Product::withTrashed()->findOrFail($id);
        $old = ['stock' => $product->stock];
        $product->update(['stock' => $validated['stock']]);

        AuditLog::log('product.stock_updated', $product, $old, ['stock' => $product->stock]);
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully.',
            'data' => ['stock' => $product->stock],
        ]);
    }

    public function bulkPriceUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['exists:products,id'],
            'type' => ['required', 'in:percentage,fixed'],
            'action' => ['required', 'in:increase,decrease'],
            'value' => ['required', 'numeric', 'min:0.01'],
        ]);

        $products = Product::whereIn('id', $validated['product_ids'])->get();
        $updatedCount = 0;

        foreach ($products as $product) {
            $oldPrice = (float) $product->price;
            $delta = ($validated['type'] === 'percentage')
                ? ($oldPrice * (float) $validated['value']) / 100
                : (float) $validated['value'];

            $newPrice = ($validated['action'] === 'increase') ? ($oldPrice + $delta) : ($oldPrice - $delta);
            $newPrice = max(0, round($newPrice, 2));

            $product->update(['price' => $newPrice]);
            $updatedCount++;
        }

        AuditLog::log('product.bulk_price_updated', null, null, [
            'count' => $updatedCount,
            'type' => $validated['type'],
            'action' => $validated['action'],
            'value' => $validated['value'],
        ]);
        ProductController::clearCache();

        return response()->json([
            'success' => true,
            'message' => "Successfully updated prices for {$updatedCount} products.",
            'data' => ['updated_count' => $updatedCount],
        ]);
    }

    /**
     * Upload an image or video to public/uploads/products
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200'], // 50MB max
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'mp4', 'mov', 'avi', 'webm', 'mkv'];

        if (!in_array($ext, $allowedExts)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file format. Allowed formats: JPG, PNG, WEBP, GIF, MP4, MOV, WEBM.',
            ], 422);
        }

        $destPath = public_path('uploads/products');
        if (!file_exists($destPath)) {
            @mkdir($destPath, 0755, true);
        }

        $filename = time() . '_' . Str::random(10) . '.' . $ext;
        $file->move($destPath, $filename);

        // Mirror to frontend public/uploads/products if folder exists
        $frontendPath = base_path('../foxal_frontend/public/uploads/products');
        if (file_exists(base_path('../foxal_frontend/public'))) {
            if (!file_exists($frontendPath)) {
                @mkdir($frontendPath, 0755, true);
            }
            @copy($destPath . '/' . $filename, $frontendPath . '/' . $filename);
        }

        $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
        $relativePath = '/uploads/products/' . $filename;
        $fullUrl = asset('uploads/products/' . $filename);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'data' => [
                'path' => $relativePath,
                'url' => $fullUrl,
                'filename' => $filename,
                'type' => $isVideo ? 'video' : 'image',
            ],
        ]);
    }
}
