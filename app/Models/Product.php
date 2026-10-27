<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'fragrance',
        'brand',
        'mrp',
        'price',
        'stock',
        'sku',
        'images',
        'is_active',
        'is_featured',
        'avg_rating',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'mrp' => 'decimal:2',
            'price' => 'decimal:2',
            'avg_rating' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'stock' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recalculateRating(): void
    {
        $avg = $this->approvedReviews()->avg('rating') ?: 0;
        $this->update(['avg_rating' => round($avg, 2)]);
    }
}
