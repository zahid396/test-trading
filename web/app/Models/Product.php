<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id', 'title', 'slug', 'subtitle', 'price', 'old_price',
    'discount', 'image', 'description', 'features', 'digital_info',
    'status', 'is_active', 'sort_order',
])]
class Product extends Model
{
    /** @use HasFactory<Product> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'features' => 'array',
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the orders for the product.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the effective price after discount.
     */
    public function getEffectivePriceAttribute(): float
    {
        $price = (float) $this->price;

        if ($this->discount > 0) {
            return round($price - ($price * (float) $this->discount / 100), 2);
        }

        return $price;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentageAttribute(): float
    {
        if ($this->discount > 0) {
            return (float) $this->discount;
        }

        if ($this->old_price > 0 && $this->old_price > $this->price) {
            return round((($this->old_price - $this->price) / $this->old_price) * 100, 1);
        }

        return 0;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include available products (active and in stock/status).
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('status', 'available');
    }
}
