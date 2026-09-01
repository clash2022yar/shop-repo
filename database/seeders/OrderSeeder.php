<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Coupon;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::customers()->with('addresses')->get();
        $products = Product::with('variants')->where('is_active', true)->get();
        $methods = ShippingMethod::where('is_active', true)->get();
        $coupons = Coupon::all();
        $staff = User::staff()->first();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = [
            OrderStatus::Pending, OrderStatus::Paid, OrderStatus::Processing,
            OrderStatus::Shipped, OrderStatus::Delivered, OrderStatus::Delivered,
            OrderStatus::Delivered, OrderStatus::Cancelled, OrderStatus::Returned,
        ];

        for ($i = 0; $i < 180; $i++) {
            $customer = $customers->random();
            $address = $customer->addresses->first();
            $status = $statuses[array_rand($statuses)];
            $createdAt = now()->subDays(random_int(0, 120))->subHours(random_int(0, 23));

            $lines = $products->random(random_int(1, 4));
            $itemsTotal = 0;
            $discountTotal = 0;
            $rows = [];

            foreach ($lines as $product) {
                $qty = random_int(1, 3);
                $variant = $product->variants->isNotEmpty() && random_int(0, 1)
                    ? $product->variants->random()
                    : null;

                $unit = (int) $product->price + (int) ($variant->price_diff ?? 0);
                $lineTotal = $unit * $qty;

                $itemsTotal += $lineTotal;
                $discountTotal += (int) round($lineTotal * ($product->discount_percent / 100) * 0.35);

                $rows[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'name' => $product->name,
                    'variant_title' => $variant?->title,
                    'image' => $product->primary_image,
                    'unit_price' => $unit,
                    'discount' => 0,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                ];
            }

            $method = $methods->isNotEmpty() ? $methods->random() : null;
            $shipping = $method
                ? ($method->free_from && $itemsTotal >= $method->free_from ? 0 : (int) $method->cost)
                : 0;

            $coupon = random_int(1, 100) <= 18 ? $coupons->where('type', 'percent')->first() : null;
            $couponDiscount = $coupon
                ? min((int) $coupon->max_discount ?: PHP_INT_MAX, (int) round($itemsTotal * $coupon->value / 100))
                : 0;

            $grand = max(0, $itemsTotal - $discountTotal - $couponDiscount + $shipping);

            $paid = ! in_array($status, [OrderStatus::Pending, OrderStatus::Cancelled], true);

            $order = Order::create([
                'user_id' => $customer->id,
                'status' => $status->value,
                'payment_status' => $paid ? PaymentStatus::Paid->value : PaymentStatus::Unpaid->value,
                'payment_method' => collect(['online', 'online', 'online', 'cod', 'wallet'])->random(),
                'transaction_ref' => $paid ? 'DGP-'.strtoupper(bin2hex(random_bytes(4))) : null,
                'paid_at' => $paid ? $createdAt->copy()->addMinutes(random_int(2, 90)) : null,
                'items_total' => $itemsTotal,
                'discount_total' => $discountTotal,
                'coupon_discount' => $couponDiscount,
                'shipping_cost' => $shipping,
                'grand_total' => $grand,
                'coupon_id' => $couponDiscount ? $coupon?->id : null,
                'shipping_method_id' => $method?->id,
                'receiver_name' => $address->receiver_name ?? $customer->name,
                'receiver_mobile' => $address->receiver_mobile ?? $customer->mobile,
                'province' => $address->province ?? 'تهران',
                'city' => $address->city ?? 'تهران',
                'address_line' => $address->line ?? 'خیابان ولیعصر، پلاک ۱۲',
                'postal_code' => $address->postal_code ?? '1234567890',
                'tracking_code' => in_array($status, [OrderStatus::Shipped, OrderStatus::Delivered], true)
                    ? (string) random_int(100000000000000000, 999999999999999999)
                    : null,
                'customer_note' => random_int(1, 100) <= 20 ? 'لطفاً پیش از ارسال تماس بگیرید.' : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(random_int(0, 5)),
                'shipped_at' => in_array($status, [OrderStatus::Shipped, OrderStatus::Delivered], true) ? $createdAt->copy()->addDays(1) : null,
                'delivered_at' => $status === OrderStatus::Delivered ? $createdAt->copy()->addDays(random_int(2, 6)) : null,
                'cancelled_at' => $status === OrderStatus::Cancelled ? $createdAt->copy()->addHours(random_int(1, 40)) : null,
            ]);

            foreach ($rows as $row) {
                $order->items()->create($row);
            }

            // status trail
            $trail = match ($status) {
                OrderStatus::Pending => [OrderStatus::Pending],
                OrderStatus::Cancelled => [OrderStatus::Pending, OrderStatus::Cancelled],
                OrderStatus::Paid => [OrderStatus::Pending, OrderStatus::Paid],
                OrderStatus::Processing => [OrderStatus::Pending, OrderStatus::Paid, OrderStatus::Processing],
                OrderStatus::Shipped => [OrderStatus::Pending, OrderStatus::Paid, OrderStatus::Processing, OrderStatus::Shipped],
                OrderStatus::Returned => [OrderStatus::Pending, OrderStatus::Paid, OrderStatus::Processing, OrderStatus::Shipped, OrderStatus::Delivered, OrderStatus::Returned],
                default => [OrderStatus::Pending, OrderStatus::Paid, OrderStatus::Processing, OrderStatus::Shipped, OrderStatus::Delivered],
            };

            $previous = null;
            $stamp = $createdAt->copy();

            foreach ($trail as $step) {
                $order->statusLogs()->create([
                    'user_id' => $previous === null ? $customer->id : $staff?->id,
                    'from_status' => $previous?->value,
                    'to_status' => $step->value,
                    'note' => null,
                    'created_at' => $stamp,
                    'updated_at' => $stamp,
                ]);

                $previous = $step;
                $stamp = $stamp->copy()->addHours(random_int(3, 30));
            }

            // stock movements for the fulfilled orders
            if ($paid) {
                foreach ($rows as $row) {
                    $product = $products->firstWhere('id', $row['product_id']);

                    InventoryMovement::create([
                        'product_id' => $row['product_id'],
                        'product_variant_id' => $row['product_variant_id'],
                        'user_id' => null,
                        'type' => 'sale',
                        'quantity' => -$row['quantity'],
                        'stock_after' => max(0, (int) $product->stock),
                        'reference' => $order->code,
                        'note' => 'خروج خودکار بابت ثبت سفارش',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }

        $this->command?->info('  › '.Order::count().' orders created.');
    }
}
