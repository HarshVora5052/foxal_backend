<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    public function test_customer_cannot_access_admin_endpoints(): void
    {
        $response = $this->actingAs($this->customer, 'sanctum')->getJson('/api/admin/dashboard');

        $response->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Forbidden. Admin access required.');
    }

    public function test_customer_cannot_login_via_admin_login_endpoint(): void
    {
        $response = $this->postJson('/api/auth/admin/login', [
            'email' => $this->customer->email,
            'password' => 'Password@123',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Access denied. You do not have administrator permissions.');
    }

    public function test_admin_can_login_via_admin_login_endpoint(): void
    {
        $response = $this->postJson('/api/auth/admin/login', [
            'email' => $this->admin->email,
            'password' => 'Password@123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'admin');
    }

    public function test_admin_verify_endpoint_guards(): void
    {
        // Guest
        $this->getJson('/api/admin/verify')->assertStatus(401);

        // Customer
        $this->actingAs($this->customer, 'sanctum')->getJson('/api/admin/verify')->assertStatus(403);

        // Admin
        $this->actingAs($this->admin, 'sanctum')->getJson('/api/admin/verify')
            ->assertStatus(200)
            ->assertJsonPath('data.role', 'admin');
    }

    public function test_admin_can_access_dashboard_stats(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'total_revenue',
                    'revenue_today',
                    'orders_today',
                    'new_users',
                    'total_users',
                    'low_stock_products',
                    'chart_last_30_days',
                ],
            ]);
    }

    public function test_admin_can_create_product_and_audit_log_is_created(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/admin/products', [
            'name' => 'Royal Ambergris',
            'category_id' => $this->category->id,
            'fragrance' => 'Amber & Floral',
            'mrp' => 5999.00,
            'price' => 4599.00,
            'stock' => 50,
            'description' => 'A supreme luxury extrait.',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Royal Ambergris');

        $this->assertDatabaseHas('products', [
            'name' => 'Royal Ambergris',
            'stock' => 50,
        ]);

        // Verify Audit Log entry
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'product.created',
        ]);
    }

    public function test_admin_can_update_product_stock_and_toggle_status(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        $stockResponse = $this->actingAs($this->admin, 'sanctum')->postJson("/api/admin/products/{$product->id}/stock", [
            'stock' => 75,
        ]);

        $stockResponse->assertStatus(200)
            ->assertJsonPath('data.stock', 75);

        $this->assertEquals(75, $product->fresh()->stock);

        $toggleResponse = $this->actingAs($this->admin, 'sanctum')->postJson("/api/admin/products/{$product->id}/toggle-active");
        $toggleResponse->assertStatus(200)
            ->assertJsonPath('data.is_active', false);
    }

    public function test_admin_can_export_users_csv(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')->get('/api/admin/users/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }
}
