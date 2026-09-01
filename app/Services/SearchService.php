<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SearchLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Builds the filtered product query shared by the shop, category and search
 * pages so all three behave identically.
 */
class SearchService
{
    public function paginate(Request $request, ?Category $category = null): LengthAwarePaginator
    {
        return $this->query($request, $category)
            ->paginate((int) $request->integer('per_page', config('digino.catalog.per_page')))
            ->withQueryString();
    }

    public function query(Request $request, ?Category $category = null)
    {
        $q = Product::query()
            ->with(['brand', 'images', 'category'])
            ->active()
            ->search($request->string('q')->toString() ?: null);

        if ($category) {
            $q->whereIn('category_id', $category->descendantIds());
        } elseif ($request->filled('category')) {
            $ids = collect((array) $request->input('category'))
                ->flatMap(function ($slug) {
                    $cat = Category::with('children.children')->firstWhere('slug', $slug);

                    return $cat ? $cat->descendantIds() : [];
                })->unique()->all();

            if ($ids) {
                $q->whereIn('category_id', $ids);
            }
        }

        if ($brands = array_filter((array) $request->input('brand', []))) {
            $q->whereHas('brand', fn ($b) => $b->whereIn('slug', $brands));
        }

        if ($request->filled('min_price')) {
            $q->where('price', '>=', (int) en_number($request->input('min_price')));
        }

        if ($request->filled('max_price')) {
            $q->where('price', '<=', (int) en_number($request->input('max_price')));
        }

        if ($colors = array_filter((array) $request->input('color', []))) {
            $q->whereHas('variants', fn ($v) => $v->whereIn('color_hex', $colors));
        }

        if ($request->boolean('available')) {
            $q->inStock();
        }

        if ($request->boolean('special')) {
            $q->special();
        }

        if ($request->boolean('discounted')) {
            $q->where('discount_percent', '>', 0);
        }

        if ($request->boolean('free_shipping')) {
            $q->where('free_shipping', true);
        }

        if ($request->boolean('pickup')) {
            $q->where('has_pickup', true);
        }

        if ($request->filled('seller')) {
            $q->where('is_digino_seller', $request->input('seller') === 'digino');
        }

        if ($request->filled('rating')) {
            $q->where('rating', '>=', (float) en_number($request->input('rating')));
        }

        return $q->sorted($request->string('sort')->toString() ?: null);
    }

    /** Facet counts for the sidebar, computed against the un-faceted base. */
    public function facets(Request $request, ?Category $category = null): array
    {
        $base = fn () => Product::query()->active()
            ->when($category, fn ($q) => $q->whereIn('category_id', $category->descendantIds()))
            ->search($request->string('q')->toString() ?: null);

        $categories = ($category?->children->isNotEmpty() ? $category->children : Category::active()->roots()->orderBy('sort_order')->get())
            ->map(fn (Category $c) => [
                'name' => $c->name,
                'slug' => $c->slug,
                'count' => (clone $base())->whereIn('category_id', $c->descendantIds())->count(),
            ])->filter(fn ($c) => $c['count'] > 0)->values()->all();

        $brands = Brand::active()->orderBy('sort_order')->get()
            ->map(fn (Brand $b) => [
                'name' => $b->name,
                'slug' => $b->slug,
                'count' => (clone $base())->where('brand_id', $b->id)->count(),
            ])->filter(fn ($b) => $b['count'] > 0)->sortByDesc('count')->values()->all();

        $priceRange = (clone $base())->selectRaw('MIN(price) as min, MAX(price) as max')->first();

        $ratings = collect([4, 3, 2, 1])->map(fn ($r) => [
            'value' => $r,
            'count' => (clone $base())->where('rating', '>=', $r)->count(),
        ])->all();

        $colors = \App\Models\ProductVariant::query()
            ->whereNotNull('color_hex')
            ->select('color_hex', 'color_name')
            ->distinct()
            ->limit(14)
            ->get()
            ->map(fn ($v) => ['hex' => $v->color_hex, 'name' => $v->color_name])
            ->all();

        return [
            'categories' => $categories,
            'brands' => $brands,
            'colors' => $colors,
            'ratings' => $ratings,
            'price_min' => (int) ($priceRange->min ?? 0),
            'price_max' => (int) ($priceRange->max ?? 100_000_000),
            'sellers' => [
                ['key' => 'digino', 'label' => 'دیجی‌نو (ارسال توسط دیجی‌نو)', 'count' => (clone $base())->where('is_digino_seller', true)->count()],
                ['key' => 'other', 'label' => 'فروشندگان دیگر', 'count' => (clone $base())->where('is_digino_seller', false)->count()],
            ],
            'pickup_count' => (clone $base())->where('has_pickup', true)->count(),
            'special_count' => (clone $base())->special()->count(),
        ];
    }

    /** Autocomplete payload for the header search box. */
    public function suggest(string $term): array
    {
        $limit = config('digino.search.suggest_limit');

        return [
            'products' => Product::active()->with(['brand', 'images'])->search($term)->limit($limit)->get()
                ->map(fn (Product $p) => [
                    'name' => $p->name,
                    'url' => route('products.show', $p->slug),
                    'image' => asset($p->primary_image),
                    'price' => toman($p->price),
                    'brand' => $p->brand?->name,
                ])->all(),
            'categories' => Category::active()->where('name', 'like', "%{$term}%")->limit(4)->get()
                ->map(fn (Category $c) => ['name' => $c->name, 'url' => route('categories.show', $c->slug)])->all(),
            'brands' => Brand::active()->where('name', 'like', "%{$term}%")
                ->orWhere('name_en', 'like', "%{$term}%")->limit(4)->get()
                ->map(fn (Brand $b) => ['name' => $b->name, 'url' => route('shop.index', ['brand' => [$b->slug]])])->all(),
        ];
    }

    public function log(string $term, int $count): void
    {
        if (mb_strlen($term) >= config('digino.search.min_chars')) {
            SearchLog::create([
                'term' => mb_substr($term, 0, 120),
                'results_count' => $count,
                'user_id' => auth()->id(),
            ]);
        }
    }
}
