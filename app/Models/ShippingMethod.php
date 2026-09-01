<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'icon', 'cost', 'free_from', 'estimated_days', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }

    public function costFor(int $subtotal): int
    {
        return ($this->free_from && $subtotal >= $this->free_from) ? 0 : (int) $this->cost;
    }
}
