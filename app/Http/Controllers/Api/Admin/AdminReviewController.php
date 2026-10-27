<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Review::with(['product:id,name,slug', 'user:id,name,email']);

        if ($request->has('is_approved')) {
            $query->where('is_approved', $request->boolean('is_approved'));
        }

        if ($productId = $request->input('product_id')) {
            $query->where('product_id', $productId);
        }

        $reviews = $query->latest()->paginate((int) $request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Reviews retrieved.',
            'data' => $reviews->items(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    public function approve(int $id): JsonResponse
    {
        $review = Review::with('product')->findOrFail($id);
        $old = ['is_approved' => $review->is_approved];
        $review->update(['is_approved' => true]);

        // Recalculate product rating
        $review->product?->recalculateRating();

        AuditLog::log('review.approved', $review, $old, ['is_approved' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully.',
            'data' => $review,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $review = Review::with('product')->findOrFail($id);
        $old = $review->toArray();
        $product = $review->product;
        $review->delete();

        // Recalculate product rating
        $product?->recalculateRating();

        AuditLog::log('review.deleted', null, $old, null);

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.',
            'data' => null,
        ]);
    }
}
