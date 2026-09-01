<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\SearchService;
use Illuminate\Http\Request;

class CatalogAjaxController extends Controller
{
    public function __construct(protected SearchService $search) {}

    /** Header search autocomplete. */
    public function suggest(Request $request)
    {
        $term = trim((string) $request->input('q'));

        if (mb_strlen($term) < config('digino.search.min_chars')) {
            return $this->ok('', ['products' => [], 'categories' => [], 'brands' => []]);
        }

        return $this->ok('', $this->search->suggest($term));
    }

    /** Filtering / sorting / paging on the shop pages without a reload. */
    public function products(Request $request)
    {
        $category = $request->filled('category_slug')
            ? Category::with('children.children')->firstWhere('slug', $request->input('category_slug'))
            : null;

        $products = $this->search->paginate($request, $category);

        return $this->ok('', [
            'html' => view('partials.product-grid', compact('products'))->render(),
            'pagination' => $products->links()->render(),
            'total' => $products->total(),
            'from' => $products->firstItem(),
            'to' => $products->lastItem(),
            'count_label' => 'نمایش '.fa_number($products->firstItem() ?? 0).' تا '
                .fa_number($products->lastItem() ?? 0).' از '.fa_number($products->total()).' کالا',
        ]);
    }

    /** Quick-view modal contents. */
    public function quickView(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load(['images', 'variants', 'brand', 'attributes' => fn ($q) => $q->where('is_key', true)]);

        return $this->ok('', [
            'html' => view('partials.quick-view', compact('product'))->render(),
            'title' => $product->name,
        ]);
    }

    /** Recalculate price/stock when a variant is picked on the product page. */
    public function variantPrice(Request $request, Product $product)
    {
        $variant = $product->variants()->find($request->integer('variant_id'));
        $price = $variant ? $variant->price : $product->price;
        $stock = $variant ? $variant->stock : $product->stock;

        return $this->ok('', [
            'price' => fa_number(toman($price)),
            'raw_price' => $price,
            'installment' => fa_number(toman((int) round($price / 20))),
            'stock' => $stock,
            'available' => $stock > 0,
            'stock_label' => $stock <= 0
                ? 'ناموجود'
                : ($stock <= config('digino.catalog.low_stock_threshold')
                    ? 'تنها '.fa_number($stock).' عدد در انبار'
                    : 'موجود'),
        ]);
    }

    /** Sub-categories for the mega-menu, loaded on hover. */
    public function megaMenu(Category $category)
    {
        $category->load(['children' => fn ($q) => $q->active()->with('children')]);

        return $this->ok('', [
            'html' => view('partials.mega-menu-panel', compact('category'))->render(),
        ]);
    }

    /** Product page tabs are fetched on demand. */
    public function tab(Request $request, Product $product, string $tab)
    {
        abort_unless(in_array($tab, ['description', 'specs', 'reviews', 'questions'], true), 404);

        $product->load(match ($tab) {
            'specs' => ['attributes'],
            'reviews' => ['approvedReviews.user'],
            'questions' => ['questions' => fn ($q) => $q->approved()->with(['user', 'approvedAnswers.user'])],
            default => [],
        });

        $histogram = $tab === 'reviews'
            ? collect(range(5, 1))->mapWithKeys(fn ($s) => [$s => $product->approvedReviews->where('rating', $s)->count()])->all()
            : [];

        return $this->ok('', [
            'html' => view("partials.product-tab-{$tab}", compact('product', 'histogram'))->render(),
        ]);
    }
}
