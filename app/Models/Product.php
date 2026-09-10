<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Product extends Model
{
    protected $fillable = ['name','slug','sku','description','short_description','price','discount_price','discount_percent','stock','category_id','brand_id','is_active','is_featured','views','rating','reviews_count','specs','images'];
    protected $casts = ['price' => 'integer','discount_price' => 'integer','is_active' => 'boolean','is_featured' => 'boolean','specs' => 'array','images' => 'array','rating' => 'float'];
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    public function getRouteKeyName(){ return 'slug'; }
    public function getFinalPriceAttribute(){ return $this->discount_price ?? $this->price; }
    public function getHasDiscountAttribute(){ return !is_null($this->discount_price) && $this->discount_price < $this->price; }
}
