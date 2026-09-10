<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return view('store.cart', ['cart' => $cart->summary(), 'related' => Product::where('active', true)->where('sku', 'not like', 'DG-P%')->inRandomOrder()->take(6)->get()]);
    }

    public function update(Request $r, Product $product, CartService $cart)
    {
        $d = $r->validate(['quantity' => 'required|integer|min:0|max:99']);
        $summary = $cart->update($product, $d['quantity']);

        return response()->json(['message' => $d['quantity'] ? 'سبد خرید به‌روز شد.' : 'کالا از سبد حذف شد.', 'count' => $summary['count'], 'html' => view('partials.cart-content', ['cart' => $summary])->render()]);
    }

    public function add(Request $r, Product $product, CartService $cart)
    {
        $d = $r->validate(['quantity' => 'sometimes|integer|min:1|max:99']);
        $qty = (int) session('cart.'.$product->id, 0) + ($d['quantity'] ?? 1);
        if ($qty > 99) {
            throw ValidationException::withMessages(['quantity' => 'حداکثر تعداد مجاز ۹۹ عدد است.']);
        }$summary = $cart->update($product, $qty);

        return response()->json(['message' => 'کالا به سبد خرید اضافه شد.', 'count' => $summary['count']]);
    }

    public function coupon(Request $r, CartService $cart)
    {
        $d = $r->validate(['code' => 'required|string|max:50']);
        $c = Coupon::where('code', strtoupper(trim($d['code'])))->first();
        $s = $cart->summary();
        if (! $c || ! $c->active || $c->expires_at->endOfDay()->isPast() || $c->used >= $c->usage_limit || $s['subtotal'] < $c->min_total) {
            throw ValidationException::withMessages(['code' => 'کد تخفیف نامعتبر است یا شرایط استفاده از آن برقرار نیست.']);
        }session(['coupon' => $c->code]);

        return response()->json(['message' => 'کد تخفیف اعمال شد.', 'reload' => true]);
    }

    public function checkout(CartService $cart)
    {
        $summary = $cart->summary();
        if (! $summary['count']) {
            return redirect('/cart');
        }if (! session('checkout_token')) {
            session(['checkout_token' => (string) Str::uuid()]);
        }

return view('store.checkout', ['cart' => $summary, 'addresses' => auth()->user()->addresses]);
    }

    public function place(Request $r, OrderService $orders)
    {
        $d = $r->validate(['address_id' => 'required|integer', 'checkout_token' => 'required|uuid', 'note' => 'nullable|string|max:1000', 'payment_method' => 'required|in:cod']);
        $old = $r->user()->orders()->where('checkout_token', $d['checkout_token'])->first();
        if ($old) {
            return response()->json(['redirect' => '/account/orders/'.$old->id]);
        }abort_unless(hash_equals((string) session('checkout_token'), $d['checkout_token']), 419);
        $a = $r->user()->addresses()->findOrFail($d['address_id']);
        $order = $orders->place($r->user(), $a->only(['name', 'phone', 'city', 'postal_code', 'address']), $d['checkout_token'], $d['note'] ?? null);
        session()->forget(['cart', 'coupon', 'checkout_token']);

        return response()->json(['message' => 'سفارش شما با موفقیت ثبت شد.', 'redirect' => '/account/orders/'.$order->id.'?placed=1']);
    }
}
