<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category_id', 'name', 'slug', 'brand', 'sku', 'description', 'price', 'old_price', 'stock', 'image', 'images', 'specs', 'color', 'featured', 'active'])]
class Product extends Model
{
    protected function casts(): array
    {
        return ['images' => 'array', 'specs' => 'array', 'active' => 'boolean', 'featured' => 'boolean', 'price' => 'integer', 'old_price' => 'integer'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountAttribute()
    {
        return $this->old_price > $this->price ? (int) round((1 - $this->price / $this->old_price) * 100) : 0;
    }
}
