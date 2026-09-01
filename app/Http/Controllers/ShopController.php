<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Services\SearchService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(protected SearchService $search) {}

    public function index(Request $request)
    {
        return view('pages.shop', [
            'title' => 'همه محصولات',
            'products' => $this->search->paginate($request),
            'facets' => $this->search->facets($request),
            'category' => null,
            'breadcrumbs' => [['label' => 'همه محصولات', 'url' => route('shop.index')]],
        ]);
    }

    public function special(Request $request)
    {
        $request->merge(['special' => 1]);

        return view('pages.shop', [
            'title' => 'فروش ویژه دیجی‌نو',
            'subtitle' => 'پیشنهادهای شگفت‌انگیز با تخفیف‌های واقعی و زمان‌دار',
            'products' => $this->search->paginate($request),
            'facets' => $this->search->facets($request),
            'category' => null,
            'breadcrumbs' => [['label' => 'فروش ویژه', 'url' => route('shop.special')]],
        ]);
    }

    public function brands()
    {
        return view('pages.brands', [
            'brands' => Brand::active()->withCount(['products' => fn ($q) => $q->active()])
                ->orderByDesc('is_featured')->orderBy('name')->get(),
        ]);
    }

    public function brand(Request $request, Brand $brand)
    {
        $request->merge(['brand' => [$brand->slug]]);

        return view('pages.shop', [
            'title' => 'محصولات '.$brand->name,
            'subtitle' => $brand->description,
            'brand' => $brand,
            'products' => $this->search->paginate($request),
            'facets' => $this->search->facets($request),
            'category' => null,
            'breadcrumbs' => [
                ['label' => 'برندها', 'url' => route('brands.index')],
                ['label' => $brand->name, 'url' => route('brands.show', $brand)],
            ],
        ]);
    }
}
