<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function getOrCreateCart(Request $request): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product.category']);

        $items = $cart->items->map(function (CartItem $item) {
            $product = $item->product;
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $product?->name,
                'slug' => $product?->slug,
                'price' => (float) $product?->price,
                'mrp' => (float) $product?->mrp,
                'images' => $product?->images,
                'quantity' => $item->quantity,
                'stock' => $product?->stock,
                'in_stock' => $product ? ($product->stock >= $item->quantity) : false,
                'subtotal' => round(($product ? (float) $product->price : 0) * $item->quantity, 2),
            ];
        });

        $subtotal = $items->sum('subtotal');

        return response()->json([
            'success' => true,
            'message' => 'Cart retrieved successfully.',
            'data' => [
                'id' => $cart->id,
                'items' => $items,
                'total_items' => $items->sum('quantity'),
                'subtotal' => round($subtotal, 2),
            ],
        ]);
    }

    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $product = Product::findOrFail($validated['product_id']);

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This product is currently unavailable.',
                'data' => null,
            ], 422);
        }

        $cart = $this->getOrCreateCart($request);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $newQuantity = $cartItem ? ($cartItem->quantity + $quantity) : $quantity;

        // Stock Validation
        if ($newQuantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Only {$product->stock} unit(s) available in stock.",
                'data' => null,
            ], 422);
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $newQuantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart.',
            'data' => $cartItem,
        ]);
    }

    public function updateItem(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getOrCreateCart($request);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->firstOrFail();
        $product = $cartItem->product;

        if ($validated['quantity'] > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Only {$product->stock} unit(s) available in stock.",
                'data' => null,
            ], 422);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated.',
            'data' => $cartItem,
        ]);
    }

    public function removeItem(Request $request, int $id): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        CartItem::where('cart_id', $cart->id)->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'data' => null,
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared.',
            'data' => null,
        ]);
    }

    public function mergeGuestCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getOrCreateCart($request);

        foreach ($validated['items'] as $guestItem) {
            $product = Product::find($guestItem['product_id']);
            if (!$product || !$product->is_active || $product->stock <= 0) {
                continue;
            }

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            $totalQty = $cartItem ? ($cartItem->quantity + $guestItem['quantity']) : $guestItem['quantity'];
            $finalQty = min($totalQty, $product->stock);

            if ($cartItem) {
                $cartItem->update(['quantity' => $finalQty]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $finalQty,
                ]);
            }
        }

        return $this->index($request);
    }
}
