<?php

namespace App\Models;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'order_id', 'title', 'body', 'rating',
        'pros', 'cons', 'recommends', 'status', 'reject_reason', 'is_buyer', 'likes', 'dislikes',
    ];

    protected function casts(): array
    {
        return [
            'pros' => 'array',
            'cons' => 'array',
            'recommends' => 'boolean',
            'is_buyer' => 'boolean',
            'status' => ReviewStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn (self $r) => $r->product?->refreshRating());
        static::deleted(fn (self $r) => $r->product?->refreshRating());
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved($q)
    {
        return $q->where('status', ReviewStatus::Approved->value);
    }

    public function scopePending($q)
    {
        return $q->where('status', ReviewStatus::Pending->value);
    }
}
