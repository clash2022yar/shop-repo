<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Turns a cart into an order, atomically, while keeping stock honest.
 */
class CheckoutService
{
    public function __construct(protected CartService $cart) {}

    public function place(Address $address, ?ShippingMethod $method, string $paymentMethod, ?string $note = null): Order
    {
        $items = $this->cart->selectedItems();

        if ($items->isEmpty()) {
            throw new RuntimeException('هیچ کالایی برای ثبت سفارش انتخاب نشده است.');
        }

        return DB::transaction(function () use ($items, $address, $method, $paymentMethod, $note) {
            $subtotal = $this->cart->subtotal();
            $couponDiscount = $this->cart->couponDiscount();
            $shipping = $this->cart->shippingCost($method);
            $coupon = $this->cart->cart(false)?->coupon;

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => OrderStatus::Pending->value,
                'payment_status' => PaymentStatus::Unpaid->value,
                'payment_method' => $paymentMethod,
                'items_total' => $subtotal,
                'discount_total' => $this->cart->productDiscount(),
                'coupon_discount' => $couponDiscount,
                'shipping_cost' => $shipping,
                'tax_total' => 0,
                'grand_total' => max(0, $subtotal - $couponDiscount + $shipping),
                'coupon_id' => $coupon?->id,
                'shipping_method_id' => $method?->id,
                'receiver_name' => $address->receiver_name,
                'receiver_mobile' => $address->receiver_mobile,
                'province' => $address->province,
                'city' => $address->city,
                'address_line' => $address->full,
                'postal_code' => $address->postal_code,
                'customer_note' => $note,
            ]);

            foreach ($items as $item) {
                $product = $item->product;
                $variant = $item->variant;

                // Re-check stock inside the transaction to avoid overselling.
                $available = (int) ($variant?->stock ?? $product->stock);

                if ($available < $item->quantity) {
                    throw new RuntimeException("موجودی «{$product->name}» کافی نیست.");
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'name' => $product->name,
                    'variant_title' => $variant?->title,
                    'image' => $product->primary_image,
                    'unit_price' => $item->unit_price,
                    'discount' => max(0, ($product->compare_at_price ?: $product->price) - $product->price),
                    'quantity' => $item->quantity,
                    'line_total' => $item->line_total,
                ]);

                if ($variant) {
                    $variant->decrement('stock', $item->quantity);
                }

                $product->decrement('stock', $item->quantity);
                $product->increment('sold_count', $item->quantity);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => -$item->quantity,
                    'stock_after' => max(0, $product->stock - $item->quantity),
                    'reference' => $order->code,
                    'note' => 'ثبت سفارش',
                ]);
            }

            if ($coupon) {
                $coupon->increment('used_count');

                if (Auth::check()) {
                    DB::table('coupon_user')->insert([
                        'coupon_id' => $coupon->id,
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $order->statusLogs()->create([
                'user_id' => Auth::id(),
                'to_status' => OrderStatus::Pending->value,
                'note' => 'سفارش ثبت شد.',
            ]);

            // Only the selected lines leave the cart.
            $this->cart->removeSelected();
            $this->cart->removeCoupon();

            return $order->fresh('items');
        });
    }

    /**
     * Settle payment for an order. With no gateway credentials configured the
     * store falls back to a manual confirmation flow, which is honest about
     * what it does rather than pretending a payment happened.
     */
    public function markPaid(Order $order, ?string $reference = null): Order
    {
        $order->forceFill([
            'payment_status' => PaymentStatus::Paid->value,
            'transaction_ref' => $reference,
            'paid_at' => now(),
        ])->save();

        $order->transitionTo(OrderStatus::Paid, 'پرداخت تأیید شد.', Auth::id());

        return $order->refresh();
    }

    /** Cancel an order and return every reserved unit to stock. */
    public function cancel(Order $order, ?string $reason = null): Order
    {
        return DB::transaction(function () use ($order, $reason) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    $item->product->decrement('sold_count', min($item->quantity, $item->product->sold_count));

                    InventoryMovement::create([
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'user_id' => Auth::id(),
                        'type' => 'return',
                        'quantity' => $item->quantity,
                        'stock_after' => $item->product->stock + $item->quantity,
                        'reference' => $order->code,
                        'note' => 'لغو سفارش',
                    ]);
                }

                $item->variant?->increment('stock', $item->quantity);
            }

            $order->transitionTo(OrderStatus::Cancelled, $reason ?? 'سفارش لغو شد.', Auth::id());

            return $order->refresh();
        });
    }
}
