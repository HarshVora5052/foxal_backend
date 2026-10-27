<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($validated['code'])))->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.',
                'data' => null,
            ], 422);
        }

        $check = $coupon->isValidFor((float) $validated['subtotal'], $request->user());
        if (!$check['valid']) {
            return response()->json([
                'success' => false,
                'message' => $check['message'],
                'data' => null,
            ], 422);
        }

        $discount = $coupon->calculateDiscount((float) $validated['subtotal']);
        $newTotal = max(0, (float) $validated['subtotal'] - $discount);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'data' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => (float) $coupon->value,
                'discount' => $discount,
                'new_subtotal' => $newTotal,
            ],
        ]);
    }
}
