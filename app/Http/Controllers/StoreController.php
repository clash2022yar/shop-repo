<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home()
    {
        return view('store.home', ['featured' => Product::where('active', true)->where('featured', true)->with('category')->withAvg(['reviews as rating' => fn ($q) => $q->where('approved', true)], 'rating')->take(6)->get(), 'popular' => Product::where('active', true)->with('category')->withCount(['reviews' => fn ($q) => $q->where('approved', true)])->orderByDesc('reviews_count')->orderBy('id')->skip(2)->take(6)->get(), 'banners' => Banner::where('active', true)->get(), 'articles' => Article::where('published', true)->latest()->take(3)->get()]);
    }

    public function products(Request $r, ?string $slug = null)
    {
        $q = Product::where('active', true)->with('category')->withAvg(['reviews' => fn ($q) => $q->where('approved', true)], 'rating')->withCount(['reviews' => fn ($q) => $q->where('approved', true)]);
        $category = $slug ? Category::where('slug', $slug)->firstOrFail() : null;
        if ($category) {
            $q->where('category_id', $category->id);
        }
        if ($r->filled('q')) {
            $term = mb_substr($r->string('q'), 0, 100);
            $q->where(fn ($q) => $q->where('name', 'like', "%$term%")->orWhere('brand', 'like', "%$term%"));
        }
        if ($r->filled('category')) {
            $q->whereHas('category', fn ($q) => $q->where('slug', $r->string('category')));
        }
        if ($r->filled('brand')) {
            $q->whereIn('brand', array_slice((array) $r->input('brand'), 0, 20));
        }
        if ($r->boolean('available')) {
            $q->where('stock', '>', 0);
        }if ($r->boolean('sale')) {
            $q->whereColumn('old_price', '>', 'price');
        }
        if ($r->filled('min')) {
            $q->where('price', '>=', max(0, (int) $r->input('min')));
        }if ($r->filled('max')) {
            $q->where('price', '<=', max(0, (int) $r->input('max')));
        }
        match ($r->input('sort')) {
            'price_asc' => $q->orderBy('price'),'price_desc' => $q->orderByDesc('price'),'newest' => $q->latest(),'popular' => $q->orderByDesc('reviews_count'),default => $q->orderByDesc('featured')->orderBy('id')
        };
        $products = $q->paginate(16)->withQueryString();
        if ($r->expectsJson()) {
            return response()->json(['html' => view('partials.product-grid', compact('products'))->render(), 'count' => $products->total()]);
        }

        return view('store.products', compact('products', 'category') + ['brands' => Product::where('active', true)->distinct()->pluck('brand')]);
    }

    public function suggest(Request $r)
    {
        $term = mb_substr($r->string('q'), 0, 100);
        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

return Product::where('active', true)->where(fn ($q) => $q->where('name', 'like', "%$term%")->orWhere('brand', 'like', "%$term%"))->limit(6)->get(['name', 'slug', 'image', 'price']);
    }

    public function product(string $slug)
    {
        $product = Product::where('slug', $slug)->where('active', true)->with('category')->with(['reviews' => fn ($q) => $q->where('approved', true)->with('user')->latest()])->firstOrFail();
        $recent = session('recent', []);
        session(['recent' => array_slice(array_unique([$product->id, ...$recent]), 0, 12)]);

        return view('store.product', ['product' => $product, 'related' => Product::where('active', true)->where('id', '!=', $product->id)->where('category_id', $product->category_id)->take(6)->get()]);
    }

    public function categories()
    {
        return view('store.categories');
    }

    public function about()
    {
        return view('store.about');
    }

    public function help(string $page = 'guide')
    {
        abort_unless(in_array($page, ['guide', 'shipping', 'returns', 'privacy', 'contact', 'faq']), 404);

        return view('store.help', compact('page'));
    }

    public function journal(?string $slug = null)
    {
        if ($slug) {
            return view('store.article', ['article' => Article::where('slug', $slug)->where('published', true)->firstOrFail()]);
        }

return view('store.journal', ['articles' => Article::where('published', true)->latest()->paginate(9)]);
    }

    public function subscribe(Request $r)
    {
        $d = $r->validate(['email' => 'required|email|max:190']);
        Subscriber::firstOrCreate($d);

        return response()->json(['message' => 'ایمیل شما در فهرست خبرنامه ثبت شد.']);
    }

    public function review(Request $r, Product $product)
    {
        abort_unless($product->active, 404);
        $d = $r->validate(['rating' => 'required|integer|between:1,5', 'body' => 'required|string|min:10|max:2000']);
        $review = Review::firstOrNew(['product_id' => $product->id, 'user_id' => $r->user()->id]);
        $review->fill($d);
        $review->user_id = $r->user()->id;
        $review->approved = false;
        $review->save();

        return response()->json(['message' => 'دیدگاه شما ثبت شد و پس از بررسی نمایش داده می‌شود.']);
    }
}
