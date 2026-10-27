<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Foxal Haute Parfumerie Admin
|--------------------------------------------------------------------------
*/

$renderAdminView = function () {
    try {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
    } catch (\Throwable $e) {
        $categories = collect();
    }
    return view('admin.dashboard', compact('categories'));
};

Route::get('/admin', $renderAdminView);

Route::get('/', function (Request $request) use ($renderAdminView) {
    if ($request->wantsJson() && !$request->acceptsHtml()) {
        return response()->json([
            'app' => 'Foxal Haute Parfumerie API',
            'status' => 'online',
            'version' => '1.0',
            'feed' => url('/api/feed'),
            'admin_console' => url('/admin'),
            'admin_login' => url('/api/auth/admin/login'),
        ]);
    }

    return $renderAdminView();
});
