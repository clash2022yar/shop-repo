<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id', 'product_variant_id', 'user_id', 'type',
        'quantity', 'stock_after', 'reference', 'note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in' => 'ورود به انبار',
            'out' => 'خروج از انبار',
            'adjust' => 'اصلاح موجودی',
            'return' => 'مرجوعی',
            default => $this->type,
        };
    }
}
