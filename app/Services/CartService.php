<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * The single source of truth for everything cart related.
 *
 * A cart row is keyed either by the authenticated user or, for guests, by the
 * session id. When a guest logs in the two carts are merged so nothing that
 * was picked before signing in gets lost.
 */
class CartService
{
    protected ?Cart $cart = null;

    public function cart(bool $create = true): ?Cart
    {
        if ($this->cart) {
            return $this->cart;
        }

        $query = Cart::query()->with([
            'items.product.images',
            'items.product.brand',
            'items.variant',
            'coupon',
        ]);

        $cart = Auth::check()
            ? $query->firstWhere('user_id', Auth::id())
            : $query->firstWhere('session_id', session()->getId());

        if (! $cart && $create) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'session_id' => Auth::check() ? null : session()->getId(),
            ]);
            $cart->setRelation('items', collect());
        }

        return $this->cart = $cart;
    }

    /** @return Collection<int,CartItem> */
    public function items(): Collection
    {
        return $this->cart(false)?->items ?? collect();
    }

    public function selectedItems(): Collection
    {
        return $this->items()->where('is_selected', true);
    }

    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }

    // ------------------------------------------------------------ mutations

    /**
     * @return array{ok:bool,message:string,item:?CartItem}
     */
    public function add(Product $product, ?ProductVariant $variant = null, int $quantity = 1): array
    {
        if (! $product->is_active) {
            return ['ok' => false, 'message' => 'این کالا در حال حاضر قابل سفارش نیست.', 'item' => null];
        }

        $available = (int) ($variant?->stock ?? $product->stock);

        if ($available < 1) {
            return ['ok' => false, 'message' => 'موجودی این کالا به پایان رسیده است.', 'item' => null];
        }

        $cart = $this->cart();
        $max = min($product->max_per_order ?: 5, config('digino.cart.max_qty_per_item'), $available);

        $item = $cart->items()->firstOrNew([
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ]);

        $newQty = ($item->quantity ?? 0) + $quantity;

        if ($newQty > $max) {
            if (($item->quantity ?? 0) >= $max) {
                return [
                    'ok' => false,
                    'message' => 'حداکثر تعداد مجاز برای این کالا '.fa_number($max).' عدد است.',
                    'item' => $item->exists ? $item : null,
                ];
            }
            $newQty = $max;
        }

        $item->quantity = $newQty;
        $item->is_selected = true;
        $item->save();

        $this->cart = null;

        return ['ok' => true, 'message' => 'کالا به سبد خرید اضافه شد.', 'item' => $item];
    }

    public function updateQuantity(CartItem $item, int $quantity): array
    {
        $max = min(
            $item->product->max_per_order ?: 5,
            config('digino.cart.max_qty_per_item'),
            $item->available_stock
        );

        if ($quantity < 1) {
            return $this->remove($item);
        }

        if ($quantity > $max) {
            $quantity = $max;
        }

        $item->update(['quantity' => $quantity]);
        $this->cart = null;

        return ['ok' => true, 'message' => 'تعداد به‌روزرسانی شد.', 'quantity' => $quantity];
    }

    public function remove(CartItem $item): array
    {
        $item->delete();
        $this->cart = null;

        return ['ok' => true, 'message' => 'کالا از سبد خرید حذف شد.', 'quantity' => 0];
    }

    public function toggleSelection(CartItem $item, bool $selected): void
    {
        $item->update(['is_selected' => $selected]);
        $this->cart = null;
    }

    public function selectAll(bool $selected): void
    {
        $this->cart(false)?->items()->update(['is_selected' => $selected]);
        $this->cart = null;
    }

    public function removeSelected(): int
    {
        $count = (int) $this->cart(false)?->items()->where('is_selected', true)->delete();
        $this->cart = null;

        return $count;
    }

    public function clear(): void
    {
        $cart = $this->cart(false);
        $cart?->items()->delete();
        $cart?->update(['coupon_id' => null]);
        $this->cart = null;
    }

    // -------------------------------------------------------------- coupon

    /** @return array{ok:bool,message:string} */
    public function applyCoupon(string $code): array
    {
        $cart = $this->cart();
        $coupon = Coupon::usable()->where('code', mb_strtoupper(trim($code)))->first();

        if (! $coupon) {
            return ['ok' => false, 'message' => 'کد تخفیف نامعتبر یا منقضی شده است.'];
        }

        if ($coupon->is_exhausted) {
            return ['ok' => false, 'message' => 'ظرفیت استفاده از این کد تخفیف تکمیل شده است.'];
        }

        $subtotal = $this->subtotal();

        if ($subtotal < $coupon->min_order_total) {
            return [
                'ok' => false,
                'message' => 'حداقل مبلغ سفارش برای این کد '.fa_number(toman($coupon->min_order_total)).' تومان است.',
            ];
        }

        if (Auth::check() && $coupon->per_user_limit) {
            $used = $coupon->newQuery()->getConnection()->table('coupon_user')
                ->where('coupon_id', $coupon->id)->where('user_id', Auth::id())->count();

            if ($used >= $coupon->per_user_limit) {
                return ['ok' => false, 'message' => 'شما پیش‌تر از این کد تخفیف استفاده کرده‌اید.'];
            }
        }

        $cart->update(['coupon_id' => $coupon->id]);
        $this->cart = null;

        return ['ok' => true, 'message' => 'کد تخفیف با موفقیت اعمال شد.'];
    }

    public function removeCoupon(): void
    {
        $this->cart(false)?->update(['coupon_id' => null]);
        $this->cart = null;
    }

    // --------------------------------------------------------------- totals

    public function subtotal(): int
    {
        return (int) $this->selectedItems()->sum(fn (CartItem $i) => $i->line_total);
    }

    /** Total the items would have cost at their pre-discount price. */
    public function grossTotal(): int
    {
        return (int) $this->selectedItems()->sum(
            fn (CartItem $i) => ($i->product->compare_at_price ?: $i->product->price) * $i->quantity
        );
    }

    public function productDiscount(): int
    {
        return max(0, $this->grossTotal() - $this->subtotal());
    }

    public function couponDiscount(): int
    {
        $coupon = $this->cart(false)?->coupon;

        return $coupon ? $coupon->discountFor($this->subtotal()) : 0;
    }

    public function shippingCost(?ShippingMethod $method = null): int
    {
        $payable = $this->subtotal() - $this->couponDiscount();

        if ($payable <= 0) {
            return 0;
        }

        if ($method) {
            return $method->costFor($payable);
        }

        $freeFrom = (int) digino('free_shipping_from', config('digino.checkout.free_shipping_from'));

        return $payable >= $freeFrom ? 0 : (int) digino('shipping_cost', config('digino.checkout.default_shipping_cost'));
    }

    public function grandTotal(?ShippingMethod $method = null): int
    {
        return max(0, $this->subtotal() - $this->couponDiscount() + $this->shippingCost($method));
    }

    /** Everything the UI needs in one payload — used by every AJAX response. */
    public function summary(?ShippingMethod $method = null): array
    {
        $subtotal = $this->subtotal();
        $couponDiscount = $this->couponDiscount();
        $shipping = $this->shippingCost($method);
        $freeFrom = (int) digino('free_shipping_from', config('digino.checkout.free_shipping_from'));

        return [
            'count' => $this->count(),
            'selected_count' => (int) $this->selectedItems()->sum('quantity'),
            'lines' => $this->items()->count(),
            'subtotal' => $subtotal,
            'product_discount' => $this->productDiscount(),
            'coupon_discount' => $couponDiscount,
            'coupon_code' => $this->cart(false)?->coupon?->code,
            'shipping_cost' => $shipping,
            'free_shipping' => $shipping === 0 && $subtotal > 0,
            'free_shipping_remaining' => max(0, $freeFrom - ($subtotal - $couponDiscount)),
            'grand_total' => max(0, $subtotal - $couponDiscount + $shipping),
            'formatted' => [
                'subtotal' => toman($subtotal),
                'product_discount' => toman($this->productDiscount()),
                'coupon_discount' => toman($couponDiscount),
                'shipping_cost' => $shipping === 0 ? 'رایگان' : toman($shipping),
                'grand_total' => toman(max(0, $subtotal - $couponDiscount + $shipping)),
            ],
        ];
    }

    /** Merge the guest cart into the user cart right after login. */
    public function mergeGuestCart(int $userId, string $sessionId): void
    {
        $guest = Cart::with('items')->firstWhere('session_id', $sessionId);

        if (! $guest || $guest->items->isEmpty()) {
            $guest?->delete();

            return;
        }

        $target = Cart::firstOrCreate(['user_id' => $userId]);

        foreach ($guest->items as $item) {
            $existing = $target->items()->firstOrNew([
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
            ]);

            $existing->quantity = min(
                ($existing->quantity ?? 0) + $item->quantity,
                config('digino.cart.max_qty_per_item')
            );
            $existing->is_selected = true;
            $existing->save();
        }

        if ($guest->coupon_id && ! $target->coupon_id) {
            $target->update(['coupon_id' => $guest->coupon_id]);
        }

        $guest->items()->delete();
        $guest->delete();

        $this->cart = null;
    }

    /** Drop lines that went out of stock or were deactivated since being added. */
    public function pruneUnavailable(): array
    {
        $removed = [];

        foreach ($this->items() as $item) {
            if (! $item->product || ! $item->product->is_active || $item->available_stock < 1) {
                $removed[] = $item->product?->name ?? 'کالای حذف‌شده';
                $item->delete();

                continue;
            }

            if ($item->quantity > $item->available_stock) {
                $item->update(['quantity' => $item->available_stock]);
            }
        }

        if ($removed) {
            $this->cart = null;
        }

        return $removed;
    }
}
