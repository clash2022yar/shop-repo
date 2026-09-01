<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $ids = collect(explode(',', (string) $request->input('ids')))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->take(config('digino.catalog.max_compare'));

        $products = $ids->isEmpty()
            ? collect()
            : Product::active()->whereIn('id', $ids)
                ->with(['brand', 'images', 'attributes', 'category'])->get();

        // Union of every attribute name across the selected products so the
        // comparison table has one row per specification.
        $rows = $products->flatMap(fn (Product $p) => $p->attributes->pluck('name'))
            ->unique()->values();

        return view('pages.compare', compact('products', 'rows'));
    }
}
