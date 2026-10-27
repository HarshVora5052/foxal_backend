<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Admin\AdminAuditLogController;
use App\Http\Controllers\Api\Admin\AdminCategoryController;
use App\Http\Controllers\Api\Admin\AdminContactController;
use App\Http\Controllers\Api\Admin\AdminCouponController;
use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\Admin\AdminProductController;
use App\Http\Controllers\Api\Admin\AdminReviewController;
use App\Http\Controllers\Api\Admin\AdminSettingController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Prefix: /api configured in bootstrap/app.php)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. AUTHENTICATION ROUTES (/api/auth/*)
// ==========================================
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:auth');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:auth');
    Route::post('admin/login', [AuthController::class, 'adminLogin'])->middleware('throttle:auth');
    Route::match(['get', 'post'], 'auto-login', [AuthController::class, 'autoLogin']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:auth');
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    // Authenticated Auth Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });
});


// ==========================================
// 2. PUBLIC STORE ROUTES (/api/*)
// ==========================================
// All-in-one Encrypted Product Feed for Frontend
Route::get('feed', [ProductController::class, 'feed']);
Route::get('products/feed', [ProductController::class, 'feed']);
Route::post('feed/decrypt', [ProductController::class, 'decryptFeed']);

// Public Products APIs
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);
Route::get('products/{slug}/reviews', [ProductController::class, 'reviews']);

// Categories APIs
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{slug}', [CategoryController::class, 'show']);

Route::post('contact', [ContactController::class, 'store']);
Route::post('upload', [AdminProductController::class, 'upload']);
Route::post('cart/sync', [CartController::class, 'sync']);

// Public Settings & Announcements
Route::get('settings/announcement', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'announcement' => \App\Models\Setting::get('announcement_text', 'Complimentary luxury sample with every order over ₹1,500.'),
        ],
    ]);
});

// ==========================================
// 3. CUSTOMER AUTHENTICATED ROUTES (auth:sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    // Review Submission (one per product, requires moderation)
    Route::post('products/{slug}/reviews', [ProductController::class, 'storeReview']);

    // Cart Management
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('items', [CartController::class, 'addItem']);
        Route::put('items/{id}', [CartController::class, 'updateItem']);
        Route::delete('items/{id}', [CartController::class, 'removeItem']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::post('merge', [CartController::class, 'mergeGuestCart']);
    });

    // Wishlist
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('{productId}', [WishlistController::class, 'toggle']);
        Route::delete('{productId}', [WishlistController::class, 'destroy']);
    });

    // Address Management (Encrypted fields)
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('/', [AddressController::class, 'store']);
        Route::get('{id}', [AddressController::class, 'show']);
        Route::put('{id}', [AddressController::class, 'update']);
        Route::delete('{id}', [AddressController::class, 'destroy']);
        Route::post('{id}/default', [AddressController::class, 'setDefault']);
    });

    // Coupon Validation
    Route::post('coupons/validate', [CouponController::class, 'validateCoupon']);

    // Order Processing
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('{orderNumber}', [OrderController::class, 'show']);
        Route::post('{orderNumber}/cancel', [OrderController::class, 'cancel']);
        Route::get('{orderNumber}/invoice', [OrderController::class, 'invoice']);
    });
});

// ==========================================
// 4. ADMIN ROUTES (/api/admin/*, auth:sanctum + admin)
// ==========================================
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Admin Session Verification
    Route::get('verify', function (\Illuminate\Http\Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Admin verified.',
            'data' => $request->user(),
        ]);
    });

    // Dashboard Stats & 30-Day Chart
    Route::get('dashboard', [AdminDashboardController::class, 'stats']);

    // Products Management
    Route::post('upload', [AdminProductController::class, 'upload']);
    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index']);
        Route::post('/', [AdminProductController::class, 'store']);
        Route::post('upload', [AdminProductController::class, 'upload']);
        Route::post('bulk-price-update', [AdminProductController::class, 'bulkPriceUpdate']);
        Route::get('{id}', [AdminProductController::class, 'show']);
        Route::put('{id}', [AdminProductController::class, 'update']);
        Route::delete('{id}', [AdminProductController::class, 'destroy']);
        Route::post('{id}/restore', [AdminProductController::class, 'restore']);
        Route::post('{id}/toggle-active', [AdminProductController::class, 'toggleActive']);
        Route::post('{id}/toggle-featured', [AdminProductController::class, 'toggleFeatured']);
        Route::post('{id}/stock', [AdminProductController::class, 'updateStock']);
    });

    // Categories Management
    Route::prefix('categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index']);
        Route::post('/', [AdminCategoryController::class, 'store']);
        Route::get('{id}', [AdminCategoryController::class, 'show']);
        Route::put('{id}', [AdminCategoryController::class, 'update']);
        Route::delete('{id}', [AdminCategoryController::class, 'destroy']);
    });

    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('export', [AdminUserController::class, 'exportCsv']);
        Route::get('/', [AdminUserController::class, 'index']);
        Route::get('{id}', [AdminUserController::class, 'show']);
        Route::post('{id}/toggle-block', [AdminUserController::class, 'toggleBlock']);
        Route::post('{id}/role', [AdminUserController::class, 'updateRole']);
    });

    // Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('export', [AdminOrderController::class, 'exportCsv']);
        Route::get('/', [AdminOrderController::class, 'index']);
        Route::get('{id}', [AdminOrderController::class, 'show']);
        Route::put('{id}/status', [AdminOrderController::class, 'updateStatus']);
        Route::post('{id}/refund', [AdminOrderController::class, 'markRefunded']);
    });

    // Reviews Moderation
    Route::prefix('reviews')->group(function () {
        Route::get('/', [AdminReviewController::class, 'index']);
        Route::post('{id}/approve', [AdminReviewController::class, 'approve']);
        Route::delete('{id}', [AdminReviewController::class, 'destroy']);
    });

    // Coupons Management
    Route::prefix('coupons')->group(function () {
        Route::get('/', [AdminCouponController::class, 'index']);
        Route::post('/', [AdminCouponController::class, 'store']);
        Route::get('{id}', [AdminCouponController::class, 'show']);
        Route::put('{id}', [AdminCouponController::class, 'update']);
        Route::delete('{id}', [AdminCouponController::class, 'destroy']);
    });

    // Contact Messages
    Route::prefix('contact-messages')->group(function () {
        Route::get('/', [AdminContactController::class, 'index']);
        Route::post('{id}/mark-read', [AdminContactController::class, 'markRead']);
        Route::delete('{id}', [AdminContactController::class, 'destroy']);
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminSettingController::class, 'index']);
        Route::put('/', [AdminSettingController::class, 'update']);
    });

    // Audit Logs
    Route::get('audit-logs', [AdminAuditLogController::class, 'index']);
});
