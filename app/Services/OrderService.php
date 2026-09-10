<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function place($user, array $address, string $token, ?string $note): Order
    {
        return DB::transaction(function () use ($user, $address, $token, $note) {
            if ($old = $user->orders()->where('checkout_token', $token)->first()) {
                return $old;
            }
            $cart = app(CartService::class)->summary();
            if (! $cart['count']) {
                throw ValidationException::withMessages(['cart' => 'سبد خرید شما خالی است.']);
            }
            foreach ($cart['items'] as $item) {
                $p = Product::lockForUpdate()->find($item['product']->id);
                if (! $p || ! $p->active || $p->stock < $item['quantity']) {
                    throw ValidationException::withMessages(['stock' => 'موجودی یکی از کالاها تغییر کرده است. سبد خرید را بررسی کنید.']);
                }if ($p->price !== $item['product']->price) {
                    throw ValidationException::withMessages(['price' => 'قیمت کالا تغییر کرده است. دوباره تلاش کنید.']);
                }$p->decrement('stock', $item['quantity']);
            }
            if ($cart['coupon']) {
                $c = Coupon::lockForUpdate()->find($cart['coupon']->id);
                if (! $c->active || $c->used >= $c->usage_limit || $c->expires_at->endOfDay()->isPast() || $c->min_total > $cart['subtotal'] || $c->percent !== $cart['coupon']->percent) {
                    throw ValidationException::withMessages(['coupon' => 'کد تخفیف دیگر معتبر نیست.']);
                }$c->increment('used');
            }
            $order = $user->orders()->create(['number' => 'DG-'.strtoupper(bin2hex(random_bytes(5))), 'checkout_token' => $token, 'subtotal' => $cart['subtotal'], 'discount' => $cart['discount'], 'shipping' => $cart['shipping'], 'total' => $cart['total'], 'address' => $address, 'coupon_id' => $cart['coupon']?->id, 'note' => $note]);
            foreach ($cart['items'] as $i) {
                $order->items()->create(['product_id' => $i['product']->id, 'name' => $i['product']->name, 'image' => $i['product']->image, 'price' => $i['product']->price, 'quantity' => $i['quantity']]);
            }

            return $order;
        });
    }

    public function transition(Order $order, string $status): void
    {
        DB::transaction(function () use ($order, $status) {
            $order = Order::lockForUpdate()->findOrFail($order->id);
            $allowed = ['processing' => ['shipped', 'cancelled'], 'shipped' => ['delivered'], 'delivered' => [], 'cancelled' => []];
            if ($order->status === $status) {
                return;
            }if (! in_array($status, $allowed[$order->status] ?? [])) {
                throw ValidationException::withMessages(['status' => 'این تغییر وضعیت سفارش مجاز نیست.']);
            }if ($status === 'cancelled') {
                foreach ($order->items as $i) {
                    if ($i->product_id) {
                        Product::whereKey($i->product_id)->increment('stock', $i->quantity);
                    }
                }if ($order->coupon_id) {
                    Coupon::whereKey($order->coupon_id)->where('used', '>', 0)->decrement('used');
                }
            }$order->update(['status' => $status]);
        });
    }
}
