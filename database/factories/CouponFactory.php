<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'type' => 'percent',
            'value' => 15.00,
            'min_order_amount' => 500.00,
            'max_discount' => 300.00,
            'usage_limit' => 100,
            'usage_count' => 0,
            'per_user_limit' => 1,
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addMonth(),
        ];
    }
}
