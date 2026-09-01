<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'title', 'color_name', 'color_hex', 'option_name', 'option_value',
        'sku', 'price_diff', 'stock', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'price_diff' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saving(function (self $variant) {
            if (blank($variant->sku)) {
                $variant->sku = 'V-'.strtoupper(Str::random(10));
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute(): int
    {
        return max(0, (int) $this->product->price + (int) $this->price_diff);
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->stock > 0;
    }
}
