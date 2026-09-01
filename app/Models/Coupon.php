<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'title', 'type', 'value', 'max_discount', 'min_order_total',
        'usage_limit', 'per_user_limit', 'used_count', 'category_id',
        'starts_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeUsable($q)
    {
        return $q->where('is_active', true)
            ->where(fn ($s) => $s->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($s) => $s->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function getIsExhaustedAttribute(): bool
    {
        return $this->usage_limit !== null && $this->used_count >= $this->usage_limit;
    }

    public function getStatusLabelAttribute(): string
    {
        return match (true) {
            ! $this->is_active => 'غیرفعال',
            $this->is_expired => 'منقضی شده',
            $this->is_exhausted => 'ظرفیت تمام',
            default => 'فعال',
        };
    }

    public function getValueLabelAttribute(): string
    {
        return $this->type === 'percent'
            ? fa_number($this->value).'٪'
            : fa_number(toman($this->value)).' تومان';
    }

    /** Discount this coupon produces for a given subtotal. */
    public function discountFor(int $subtotal): int
    {
        $discount = $this->type === 'percent'
            ? (int) round($subtotal * $this->value / 100)
            : (int) $this->value;

        if ($this->max_discount) {
            $discount = min($discount, (int) $this->max_discount);
        }

        return max(0, min($discount, $subtotal));
    }
}
