<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $wishlists = Wishlist::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $products = $wishlists->pluck('product')->filter()->values();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist retrieved successfully.',
            'data' => $products,
        ]);
    }

    public function toggle(Request $request, int $productId): JsonResponse
    {
        $userId = $request->user()->id;
        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist.',
                'data' => ['in_wishlist' => false],
            ]);
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist.',
            'data' => ['in_wishlist' => true],
        ], 201);
    }

    public function destroy(Request $request, int $productId): JsonResponse
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist.',
            'data' => null,
        ]);
    }
}
