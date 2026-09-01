<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->incrementQuietly('views_count');

        $product->load([
            'images',
            'variants' => fn ($q) => $q->where('is_active', true),
            'attributes',
            'brand',
            'category.parent',
            'approvedReviews.user',
            'questions' => fn ($q) => $q->approved()->with(['user', 'approvedAnswers.user'])->latest(),
        ]);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->with(['brand', 'images'])
            ->orderByDesc('sold_count')
            ->limit(config('digino.catalog.related_limit'))
            ->get();

        $sameBrand = Product::active()
            ->where('brand_id', $product->brand_id)
            ->whereKeyNot($product->id)
            ->with(['brand', 'images'])
            ->limit(12)->get();

        $breadcrumbs = collect($product->category?->breadcrumbTrail() ?? [])
            ->map(fn ($c) => ['label' => $c->name, 'url' => route('categories.show', $c->slug)])
            ->push(['label' => $product->name_en ?: $product->name, 'url' => route('products.show', $product)])
            ->all();

        // Rating histogram for the reviews tab.
        $histogram = collect(range(5, 1))->mapWithKeys(fn ($star) => [
            $star => $product->approvedReviews->where('rating', $star)->count(),
        ])->all();

        return view('pages.product', compact('product', 'related', 'sameBrand', 'breadcrumbs', 'histogram'));
    }
}
