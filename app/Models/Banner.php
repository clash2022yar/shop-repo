<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'caption', 'cta_label', 'image', 'mobile_image', 'link',
        'position', 'bg_color', 'is_active', 'sort_order', 'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'starts_at' => 'datetime', 'ends_at' => 'datetime'];
    }

    public function scopeLive($q, ?string $position = null)
    {
        return $q->where('is_active', true)
            ->when($position, fn ($s) => $s->where('position', $position))
            ->where(fn ($s) => $s->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($s) => $s->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order');
    }

    public function getPositionLabelAttribute(): string
    {
        return match ($this->position) {
            'hero' => 'اسلایدر اصلی',
            'promo-right' => 'بنر تبلیغاتی راست',
            'promo-left' => 'بنر تبلیغاتی چپ',
            'strip' => 'نوار تبلیغاتی',
            'category' => 'سر دسته‌بندی',
            'sidebar' => 'ستون کناری',
            default => $this->position,
        };
    }
}
