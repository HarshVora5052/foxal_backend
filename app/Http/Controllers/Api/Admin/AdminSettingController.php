<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = [
            'shipping_charge' => (float) Setting::get('shipping_charge', 99.00),
            'free_shipping_threshold' => (float) Setting::get('free_shipping_threshold', 999.00),
            'gst_rate' => (float) Setting::get('gst_rate', 18.00),
            'announcement_text' => (string) Setting::get('announcement_text', 'Complimentary luxury sample with every order over ₹1,500.'),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Settings retrieved successfully.',
            'data' => $settings,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipping_charge' => ['sometimes', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['sometimes', 'numeric', 'min:0'],
            'gst_rate' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'announcement_text' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        $old = [
            'shipping_charge' => Setting::get('shipping_charge'),
            'free_shipping_threshold' => Setting::get('free_shipping_threshold'),
            'gst_rate' => Setting::get('gst_rate'),
            'announcement_text' => Setting::get('announcement_text'),
        ];

        if (array_key_exists('shipping_charge', $validated)) {
            Setting::set('shipping_charge', $validated['shipping_charge'], 'float');
        }
        if (array_key_exists('free_shipping_threshold', $validated)) {
            Setting::set('free_shipping_threshold', $validated['free_shipping_threshold'], 'float');
        }
        if (array_key_exists('gst_rate', $validated)) {
            Setting::set('gst_rate', $validated['gst_rate'], 'float');
        }
        if (array_key_exists('announcement_text', $validated)) {
            Setting::set('announcement_text', $validated['announcement_text'] ?? '', 'string');
        }

        AuditLog::log('settings.updated', null, $old, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
            'data' => [
                'shipping_charge' => (float) Setting::get('shipping_charge'),
                'free_shipping_threshold' => (float) Setting::get('free_shipping_threshold'),
                'gst_rate' => (float) Setting::get('gst_rate'),
                'announcement_text' => (string) Setting::get('announcement_text'),
            ],
        ]);
    }
}
