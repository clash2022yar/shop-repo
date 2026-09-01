<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $items = fake()->numberBetween(1, 20) * 10_000_000;
        $shipping = 4_900_000;

        return [
            'user_id' => User::factory(),
            'status' => OrderStatus::Pending->value,
            'payment_status' => PaymentStatus::Unpaid->value,
            'payment_method' => 'online',
            'items_total' => $items,
            'discount_total' => 0,
            'coupon_discount' => 0,
            'shipping_cost' => $shipping,
            'grand_total' => $items + $shipping,
            'receiver_name' => 'گیرنده آزمایشی',
            'receiver_mobile' => '09120000000',
            'province' => 'تهران',
            'city' => 'تهران',
            'address_line' => 'خیابان ولیعصر، پلاک ۱',
            'postal_code' => '1234567890',
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'status' => OrderStatus::Paid->value,
            'payment_status' => PaymentStatus::Paid->value,
            'paid_at' => now(),
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn () => [
            'status' => OrderStatus::Delivered->value,
            'payment_status' => PaymentStatus::Paid->value,
            'paid_at' => now()->subDays(4),
            'delivered_at' => now(),
        ]);
    }
}
