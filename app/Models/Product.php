<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'name_en', 'slug', 'sku', 'subtitle',
        'short_description', 'description', 'price', 'compare_at_price', 'discount_percent',
        'stock', 'max_per_order', 'warranty', 'shipping_weight', 'highlights', 'specs',
        'is_active', 'is_featured', 'is_special', 'is_digino_seller', 'has_pickup',
        'free_shipping', 'special_ends_at', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'specs' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_special' => 'boolean',
            'is_digino_seller' => 'boolean',
            'has_pickup' => 'boolean',
            'free_shipping' => 'boolean',
            'special_ends_at' => 'datetime',
            'rating' => 'float',
            'price' => 'integer',
            'compare_at_price' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name_en ?: $product->name, '-', 'fa')
                    ?: 'p-'.Str::random(8);
            }

            if (blank($product->sku)) {
                $product->sku = 'DG-'.strtoupper(Str::random(8));
            }

            // Keep compare_at_price and discount_percent consistent, whichever
            // one the admin happened to fill in.
            if ($product->discount_percent > 0 && ! $product->compare_at_price) {
                $product->compare_at_price = (int) round($product->price / (1 - $product->discount_percent / 100));
            }

            if ($product->compare_at_price && $product->compare_at_price > $product->price) {
                $product->discount_percent = (int) round(
                    (1 - $product->price / $product->compare_at_price) * 100
                );
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ---------------------------------------------------------- relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved')->latest();
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->latest();
    }

    // ------------------------------------------------------------ scopes
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeInStock(Builder $q): Builder
    {
        return $q->where('stock', '>', 0);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeSpecial(Builder $q): Builder
    {
        return $q->where('is_special', true)
            ->where(fn ($s) => $s->whereNull('special_ends_at')->orWhere('special_ends_at', '>', now()));
    }

    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        if (blank($term)) {
            return $q;
        }

        $term = trim(en_number($term));

        return $q->where(function (Builder $s) use ($term) {
            $s->where('name', 'like', "%{$term}%")
                ->orWhere('name_en', 'like', "%{$term}%")
                ->orWhere('subtitle', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('short_description', 'like', "%{$term}%")
                ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$term}%")
                    ->orWhere('name_en', 'like', "%{$term}%"))
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$term}%"));
        });
    }

    /** Sorting options offered on the shop / category pages. */
    public function scopeSorted(Builder $q, ?string $sort): Builder
    {
        return match ($sort) {
            'newest' => $q->latest('id'),
            'cheapest' => $q->orderBy('price'),
            'expensive' => $q->orderByDesc('price'),
            'bestseller' => $q->orderByDesc('sold_count'),
            'rating' => $q->orderByDesc('rating')->orderByDesc('reviews_count'),
            'discount' => $q->orderByDesc('discount_percent'),
            default => $q->orderByDesc('is_featured')->orderByDesc('sold_count')->orderByDesc('id'),
        };
    }

    // --------------------------------------------------------- accessors
    public function getFinalPriceAttribute(): int
    {
        return (int) $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_percent > 0 && $this->compare_at_price > $this->price;
    }

    public function getSavingsAttribute(): int
    {
        return $this->has_discount ? (int) ($this->compare_at_price - $this->price) : 0;
    }

    public function getPrimaryImageAttribute(): string
    {
        $image = $this->relationLoaded('images')
            ? ($this->images->firstWhere('is_primary', true) ?? $this->images->first())
            : $this->images()->orderByDesc('is_primary')->first();

        return $image?->path ?? 'images/placeholder-product.svg';
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= config('digino.catalog.low_stock_threshold');
    }

    public function getStockLabelAttribute(): string
    {
        return match (true) {
            $this->stock <= 0 => 'ناموجود',
            $this->is_low_stock => 'تنها '.fa_number($this->stock).' عدد در انبار',
            default => 'موجود',
        };
    }

    public function getInstallmentAttribute(): int
    {
        // 20 monthly instalments — matches the copy shown on the product page.
        return (int) round($this->price / 20);
    }

    /** Recalculate cached rating/review counters after moderation changes. */
    public function refreshRating(): void
    {
        $stats = $this->reviews()->where('status', 'approved')
            ->selectRaw('COUNT(*) as c, COALESCE(AVG(rating),0) as a')->first();

        $this->forceFill([
            'reviews_count' => (int) $stats->c,
            'rating' => round((float) $stats->a, 2),
        ])->saveQuietly();
    }
}
