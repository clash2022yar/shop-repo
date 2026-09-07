<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['gallery' => 'array', 'specifications' => 'array', 'is_active' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountPercentAttribute(): int
    {
        return $this->old_price ? round((1 - $this->price / $this->old_price) * 100) : 0;
    }
}
