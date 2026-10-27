<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected Address $address;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->user = User::factory()->create();
        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Nuit Sombre',
            'stock' => 50,
            'price' => 3000.00,
            'mrp' => 4000.00,
            'is_active' => true,
        ]);

        $this->address = Address::create([
            'user_id' => $this->user->id,
            'full_name' => 'Aarav Sharma',
            'phone' => '9812345678',
            'line1' => 'Flat 402, Royale Crest',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400050',
            'is_default' => true,
        ]);
    }

    public function test_order_creation_decrements_product_stock_and_creates_order_items(): void
    {
        $initialStock = $this->product->stock;
        $orderQuantity = 3;

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/orders', [
            'address_id' => $this->address->id,
            'payment_method' => 'cod',
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => $orderQuantity],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.payment_method', 'cod')
            ->assertJsonPath('data.status', 'PENDING');

        // Verify product stock was decremented
        $this->assertEquals($initialStock - $orderQuantity, $this->product->fresh()->stock);

        // Verify order in database
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'payment_method' => 'cod',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'quantity' => $orderQuantity,
        ]);
    }

    public function test_order_creation_fails_when_product_stock_is_insufficient(): void
    {
        $this->product->update(['stock' => 2]);

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/orders', [
            'address_id' => $this->address->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 5], // Exceeds stock 2
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);

        // Verify stock was not changed
        $this->assertEquals(2, $this->product->fresh()->stock);
    }

    public function test_order_creation_with_valid_coupon_applies_discount(): void
    {
        $coupon = Coupon::create([
            'code' => 'DISCOUNT20',
            'type' => 'percent',
            'value' => 20.00,
            'min_order_amount' => 1000.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/orders', [
            'address_id' => $this->address->id,
            'coupon_code' => 'DISCOUNT20',
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1], // Price 3000
            ],
        ]);

        $response->assertStatus(201);
        $order = Order::latest()->first();

        // 20% of 3000 = 600 discount
        $this->assertEquals(600.00, (float) $order->discount);
        $this->assertEquals(2400.00, (float) $order->total); // 3000 - 600 + free shipping (subtotal >= 999)
    }

    public function test_order_cancellation_restores_product_stock(): void
    {
        $this->actingAs($this->user, 'sanctum')->postJson('/api/orders', [
            'address_id' => $this->address->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 4],
            ],
        ]);

        $this->assertEquals(46, $this->product->fresh()->stock);

        $order = Order::latest()->first();

        $cancelResponse = $this->actingAs($this->user, 'sanctum')->postJson("/api/orders/{$order->order_number}/cancel");

        $cancelResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'CANCELLED');

        // Stock must be restored to 50
        $this->assertEquals(50, $this->product->fresh()->stock);
    }

    public function test_customer_can_download_invoice_pdf(): void
    {
        $this->actingAs($this->user, 'sanctum')->postJson('/api/orders', [
            'address_id' => $this->address->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
        ]);

        $order = Order::latest()->first();

        $response = $this->actingAs($this->user, 'sanctum')->get("/api/orders/{$order->order_number}/invoice");

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
