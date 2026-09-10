<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function summary(): array
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->where('active', true)->get();
        $items = [];
        $subtotal = 0;
        $count = 0;
        foreach ($products as $p) {
            $qty = (int) $cart[$p->id];
            $items[] = ['product' => $p, 'quantity' => $qty, 'total' => $p->price * $qty];
            $subtotal += $p->price * $qty;
            $count += $qty;
        }
        $coupon = null;
        $discount = 0;
        if ($code = session('coupon')) {
            $coupon = Coupon::where('code', $code)->where('active', true)->first();
            if (! $coupon || $coupon->expires_at->endOfDay()->isPast() || $coupon->used >= $coupon->usage_limit || $subtotal < $coupon->min_total) {
                session()->forget('coupon');
                $coupon = null;
            } else {
                $discount = intdiv($subtotal * $coupon->percent, 100);
            }
        }
        $shipping = $count && $subtotal < (int) Setting::getValue('free_shipping', 10000000) ? (int) Setting::getValue('shipping_cost', 65000) : 0;

        return compact('items', 'subtotal', 'discount', 'shipping', 'count', 'coupon') + ['total' => $subtotal - $discount + $shipping];
    }

    public function update(Product $product, int $quantity): array
    {
        if (! $product->active || $quantity > $product->stock) {
            throw ValidationException::withMessages(['quantity' => 'موجودی این کالا برای تعداد انتخاب‌شده کافی نیست.']);
        }
        $cart = session('cart', []);
        if ($quantity === 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $quantity;
        }session(['cart' => $cart]);

        return $this->summary();
    }
}
