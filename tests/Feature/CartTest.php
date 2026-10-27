<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'price' => 1999.00,
            'is_active' => true,
        ]);
    }

    public function test_customer_can_add_item_to_cart(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/cart/items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.quantity', 2);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    public function test_cannot_add_item_exceeding_stock(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/cart/items', [
            'product_id' => $this->product->id,
            'quantity' => 15, // Stock is 10
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_customer_can_update_cart_quantity(): void
    {
        $this->actingAs($this->user, 'sanctum')->postJson('/api/cart/items', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $cartResponse = $this->actingAs($this->user, 'sanctum')->getJson('/api/cart');
        $itemId = $cartResponse->json('data.items.0.id');

        $updateResponse = $this->actingAs($this->user, 'sanctum')->putJson("/api/cart/items/{$itemId}", [
            'quantity' => 4,
        ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.quantity', 4);
    }

    public function test_customer_can_remove_cart_item(): void
    {
        $this->actingAs($this->user, 'sanctum')->postJson('/api/cart/items', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $cartResponse = $this->actingAs($this->user, 'sanctum')->getJson('/api/cart');
        $itemId = $cartResponse->json('data.items.0.id');

        $deleteResponse = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/cart/items/{$itemId}");

        $deleteResponse->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', ['id' => $itemId]);
    }

    public function test_guest_cart_merge(): void
    {
        $p2 = Product::factory()->create(['stock' => 20]);

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/cart/merge', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 2],
                ['product_id' => $p2->id, 'quantity' => 3],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_items', 5);
    }
}
