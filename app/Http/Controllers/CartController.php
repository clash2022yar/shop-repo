<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        $removed = $this->cart->pruneUnavailable();

        $suggestions = Product::active()->inStock()
            ->with(['brand', 'images'])
            ->when($this->cart->items()->isNotEmpty(), function ($q) {
                $q->whereIn('category_id', $this->cart->items()->pluck('product.category_id')->unique())
                    ->whereNotIn('id', $this->cart->items()->pluck('product_id'));
            })
            ->orderByDesc('sold_count')
            ->limit(12)->get();

        return view('pages.cart', [
            'items' => $this->cart->items(),
            'summary' => $this->cart->summary(),
            'suggestions' => $suggestions,
            'removed' => $removed,
            'breadcrumbs' => [['label' => 'سبد خرید', 'url' => route('cart.index')]],
        ]);
    }
}
