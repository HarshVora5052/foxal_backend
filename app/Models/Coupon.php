<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_count' => 'integer',
            'per_user_limit' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValidFor(float $subtotal, ?User $user = null): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Coupon is inactive.'];
        }

        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'Coupon is not yet active.'];
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'Coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached.'];
        }

        if ($subtotal < (float) $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount of ₹{$this->min_order_amount} required to use this coupon.",
            ];
        }

        if ($user && $this->per_user_limit) {
            $userUsage = Order::where('user_id', $user->id)
                ->where('coupon_id', $this->id)
                ->whereNotIn('status', [Order::STATUS_CANCELLED])
                ->count();
            if ($userUsage >= $this->per_user_limit) {
                return ['valid' => false, 'message' => 'You have already used this coupon maximum allowed times.'];
            }
        }

        return ['valid' => true, 'message' => 'Coupon is valid.'];
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = ($subtotal * (float) $this->value) / 100;
            if ($this->max_discount !== null && $discount > (float) $this->max_discount) {
                $discount = (float) $this->max_discount;
            }
            return round($discount, 2);
        }

        // Fixed
        return min($subtotal, (float) $this->value);
    }
}
