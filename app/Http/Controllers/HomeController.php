<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $with = ['brand', 'images', 'category'];

        return view('pages.home', [
            'heroBanners' => Banner::live('hero')->get(),
            'promoBanners' => Banner::live()->whereIn('position', ['promo-right', 'promo-left'])->get(),
            'stripBanner' => Banner::live('strip')->first(),

            'popularCategories' => Category::active()->roots()->orderBy('sort_order')->limit(12)->get(),

            'specialOffers' => Product::active()->inStock()->special()->with($with)
                ->orderByDesc('discount_percent')->limit(12)->get(),

            'bestSellers' => Product::active()->with($with)
                ->orderByDesc('sold_count')->limit(12)->get(),

            'newArrivals' => Product::active()->with($with)->latest('id')->limit(12)->get(),

            'mostDiscounted' => Product::active()->inStock()->where('discount_percent', '>=', 10)
                ->with($with)->orderByDesc('discount_percent')->limit(12)->get(),

            'featuredBrands' => Brand::active()->featured()->orderBy('sort_order')->limit(12)->get(),

            'posts' => Post::published()->with('author')->limit(3)->get(),
        ]);
    }
}
