<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 499, 4999);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(2, true),
            'category_id' => Category::factory(),
            'fragrance' => fake()->randomElement(['Woody & Spicy', 'Floral & Citrus', 'Oriental Amber', 'Fresh Aquatic']),
            'brand' => 'Foxal Paris',
            'mrp' => round($price * 1.3, 2),
            'price' => $price,
            'stock' => 100,
            'sku' => 'FX-' . strtoupper(Str::random(6)),
            'images' => [
                'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800',
                'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800',
            ],
            'is_active' => true,
            'is_featured' => fake()->boolean(30),
            'avg_rating' => fake()->randomFloat(2, 3.5, 5.0),
        ];
    }
}
