<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function home()
    {
        return view('shop.home', ['featured' => Product::with('category')->where('is_featured', true)->take(7)->get(), 'latest' => Product::latest()->take(6)->get(), 'categories' => Category::where('is_active', true)->get(), 'banner' => Banner::where('is_active', true)->where('position', 'home')->orderBy('sort_order')->first()]);
    }

    public function products(Request $r)
    {
        $q = Product::with('category')->where('is_active', true);
        if ($r->filled('q')) {
            $q->where(fn ($x) => $x->where('name', 'like', '%'.$r->q.'%')->orWhere('brand', 'like', '%'.$r->q.'%'));
        }
        if ($r->filled('category')) {
            $q->whereHas('category', fn ($x) => $x->where('slug', $r->category));
        }
        if ($r->filled('brand')) {
            $q->whereIn('brand', (array) $r->brand);
        }
        if ($r->filled('min')) {
            $q->where('price', '>=', $r->integer('min'));
        }
        if ($r->filled('max')) {
            $q->where('price', '<=', $r->integer('max'));
        }
        $sort = $r->get('sort', 'popular');
        match ($sort) {
            'newest' => $q->latest(),'cheap' => $q->orderBy('price'),'expensive' => $q->orderByDesc('price'),default => $q->orderByDesc('reviews_count')
        };
        $data = ['products' => $q->paginate(12)->withQueryString(), 'categories' => Category::all(), 'brands' => Product::distinct()->pluck('brand'), 'title' => $r->filled('q') ? 'نتایج جستجو برای «'.$r->q.'»' : 'فروشگاه دیجینو'];
        if ($r->expectsJson()) {
            return response()->json(['html' => view('shop._product-grid', $data)->render()]);
        }

        return view('shop.products', $data);
    }

    public function category(Category $category, Request $r)
    {
        $r->merge(['category' => $category->slug]);

        return $this->products($r);
    }

    public function product(Product $product)
    {
        $product->load(['category', 'reviews' => fn ($q) => $q->where('is_approved', true)->latest()]);

        return view('shop.product', ['product' => $product, 'related' => Product::where('category_id', $product->category_id)->whereKeyNot($product->id)->take(5)->get()]);
    }

    public function suggestions(Request $r)
    {
        return Product::where('name', 'like', '%'.$r->get('q').'%')->take(6)->get(['id', 'name', 'slug', 'image', 'price']);
    }

    public function cart()
    {
        return view('shop.cart', $this->cartData());
    }

    public function addCart(Product $product, Request $r)
    {
        if (! $product->is_active || $product->stock < 1) {
            return response()->json(['message' => 'این کالا در حال حاضر موجود نیست'], 422);
        }
        $cart = session('cart', []);
        $qty = max(1, min($r->integer('quantity', 1), $product->stock));
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $qty, $product->stock);
        session(['cart' => $cart]);

        return response()->json(['message' => 'محصول به سبد خرید اضافه شد', 'count' => array_sum($cart), 'cart' => $this->cartData()]);
    }

    public function updateCart(Product $product, Request $r)
    {
        $cart = session('cart', []);
        $qty = max(0, min($r->integer('quantity'), $product->stock));
        if ($qty) {
            $cart[$product->id] = $qty;
        } else {
            unset($cart[$product->id]);
        }session(['cart' => $cart]);

        return response()->json(['message' => 'سبد خرید به‌روز شد', 'count' => array_sum($cart), 'cart' => $this->cartData()]);
    }

    public function removeCart(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return response()->json(['message' => 'محصول حذف شد', 'count' => array_sum($cart), 'cart' => $this->cartData()]);
    }

    public function applyCoupon(Request $r)
    {
        $coupon = Coupon::where('code', strtoupper($r->string('code')->toString()))
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->where(fn ($query) => $query->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit'))
            ->first();
        $subtotal = $this->cartData()['subtotal'];
        if (! $coupon || $subtotal < $coupon->min_order) {
            return response()->json(['message' => 'کد تخفیف معتبر نیست یا حداقل خرید رعایت نشده'], 422);
        }
        session(['coupon' => $coupon->code]);

        return response()->json(['message' => 'تخفیف با موفقیت اعمال شد', 'cart' => $this->cartData()]);
    }

    private function cartData(): array
    {
        $cart = session('cart', []);
        $items = Product::whereIn('id', array_keys($cart))->get()->map(function ($p) use ($cart) {
            $p->cart_quantity = $cart[$p->id];
            $p->line_total = $p->price * $p->cart_quantity;

            return $p;
        });
        $subtotal = $items->sum('line_total');
        $coupon = Coupon::where('code', session('coupon'))
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->where(fn ($query) => $query->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit'))
            ->first();
        $discount = $coupon ? ($coupon->type === 'percent' ? intval($subtotal * $coupon->value / 100) : $coupon->value) : 0;
        $shipping = $subtotal && $subtotal < 10000000 ? 150000 : 0;

        return compact('items', 'subtotal', 'discount', 'shipping') + ['total' => max(0, $subtotal - $discount + $shipping)];
    }

    public function checkout()
    {
        if (! count(session('cart', []))) {
            return redirect()->route('cart');
        }

        return view('shop.checkout', $this->cartData());
    }

    public function placeOrder(Request $r)
    {
        $data = $r->validate(['recipient' => 'required|string|max:100', 'phone' => 'required|string|max:20', 'city' => 'required|string|max:80', 'address' => 'required|string|max:500', 'postal_code' => 'required|string|max:20', 'payment_method' => 'required|in:cod']);
        $cart = $this->cartData();
        abort_if(! $cart['items']->count(), 422, 'سبد خرید خالی است');
        $order = DB::transaction(function () use ($data, $cart, $r) {
            $o = Order::create(['number' => 'DG-'.now()->format('ymd').'-'.str_pad((Order::max('id') ?? 0) + 1, 5, '0', STR_PAD_LEFT), 'user_id' => $r->user()->id, 'status' => 'processing', 'subtotal' => $cart['subtotal'], 'discount' => $cart['discount'], 'shipping' => $cart['shipping'], 'total' => $cart['total'], 'coupon_code' => session('coupon'), 'payment_method' => $data['payment_method'], 'payment_status' => 'pending', 'address' => $data]);
            foreach ($cart['items'] as $p) {
                $stockProduct = Product::lockForUpdate()->find($p->id);
                abort_if(! $stockProduct || $stockProduct->stock < $p->cart_quantity, 422, 'موجودی یکی از کالاها کافی نیست');
                OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'product_name' => $p->name, 'product_image' => $p->image, 'quantity' => $p->cart_quantity, 'price' => $p->price, 'total' => $p->line_total]);
                $stockProduct->decrement('stock', $p->cart_quantity);
            }
            if (session('coupon')) {
                Coupon::where('code', session('coupon'))->increment('used_count');
            }

            return $o;
        });
        session()->forget(['cart', 'coupon']);

        return response()->json(['message' => 'سفارش شما با موفقیت ثبت شد', 'redirect' => route('orders.show', $order)]);
    }

    public function review(Product $product, Request $r)
    {
        $d = $r->validate(['rating' => 'required|integer|min:1|max:5', 'body' => 'required|string|min:10|max:1000']);
        Review::updateOrCreate(['user_id' => $r->user()->id, 'product_id' => $product->id], $d + ['is_approved' => false]);

        return response()->json(['message' => 'نظر شما ثبت شد و پس از بررسی نمایش داده می‌شود']);
    }

    public function wishlist(Product $product, Request $r)
    {
        $item = $r->user()->wishlistItems()->where('product_id', $product->id)->first();
        if ($item) {
            $item->delete();
            $message = 'از علاقه‌مندی‌ها حذف شد';
            $active = false;
        } else {
            $r->user()->wishlistItems()->create(['product_id' => $product->id]);
            $message = 'به علاقه‌مندی‌ها اضافه شد';
            $active = true;
        }

        return response()->json(compact('message', 'active'));
    }

    public function newsletter(Request $r)
    {
        $email = $r->validate(['email' => 'required|email|max:190'])['email'];
        DB::table('newsletter_subscribers')->updateOrInsert(['email' => $email], ['subscribed_at' => now()]);

        return response()->json(['message' => 'عضویت شما در خبرنامه ثبت شد']);
    }

    public function about()
    {
        return view('shop.about');
    }
}
