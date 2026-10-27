<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $users = $query->withCount('orders')
            ->latest()
            ->paginate((int) $request->input('per_page', 15));

        // When transformed, $user->phone is automatically decrypted by the encrypted cast!
        return response()->json([
            'success' => true,
            'message' => 'Users list retrieved.',
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::with(['addresses', 'orders' => fn($q) => $q->latest()->limit(10)])->findOrFail($id);

        // $user->phone and $address->(full_name, phone, line1, line2) are automatically decrypted
        return response()->json([
            'success' => true,
            'message' => 'User details retrieved.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone, // Decrypted via cast
                'role' => $user->role,
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at,
                'created_at' => $user->created_at,
                'addresses' => $user->addresses, // Each address has decrypted fields
                'recent_orders' => $user->orders,
            ],
        ]);
    }

    public function toggleBlock(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Prevent admin from blocking themselves
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.',
                'data' => null,
            ], 422);
        }

        $old = ['is_active' => $user->is_active];
        $user->update(['is_active' => !$user->is_active]);

        AuditLog::log('user.toggle_block', $user, $old, ['is_active' => $user->is_active]);

        return response()->json([
            'success' => true,
            'message' => $user->is_active ? 'User account unblocked.' : 'User account blocked.',
            'data' => ['is_active' => $user->is_active],
        ]);
    }

    public function updateRole(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:customer,admin'],
        ]);

        $user = User::findOrFail($id);

        if ($user->id === $request->user()->id && $validated['role'] !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot demote your own admin account.',
                'data' => null,
            ], 422);
        }

        $old = ['role' => $user->role];
        $user->update(['role' => $validated['role']]);

        AuditLog::log('user.role_updated', $user, $old, ['role' => $user->role]);

        return response()->json([
            'success' => true,
            'message' => "User role updated to {$user->role}.",
            'data' => ['role' => $user->role],
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="foxal-users-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Last Login', 'Registered Date']);

            User::chunk(200, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone, // Decrypted
                        $user->role,
                        $user->is_active ? 'Active' : 'Blocked',
                        $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                        $user->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
