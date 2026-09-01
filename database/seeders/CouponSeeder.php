<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => 'DIGINO10',
                'title' => '۱۰٪ تخفیف خوش‌آمدگویی برای اولین خرید',
                'type' => 'percent',
                'value' => 10,
                'max_discount' => 3_000_000,
                'min_order_total' => 5_000_000,
                'usage_limit' => 2000,
                'per_user_limit' => 1,
                'used_count' => 318,
                'starts_at' => now()->subDays(40),
                'expires_at' => now()->addDays(60),
                'is_active' => true,
            ],
            [
                'code' => 'MOBILE15',
                'title' => '۱۵٪ تخفیف ویژه موبایل',
                'type' => 'percent',
                'value' => 15,
                'max_discount' => 8_000_000,
                'min_order_total' => 10_000_000,
                'usage_limit' => 500,
                'per_user_limit' => 1,
                'used_count' => 96,
                'starts_at' => now()->subDays(10),
                'expires_at' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'title' => 'کوپن ۴۹ هزار تومانی هزینه ارسال',
                'type' => 'fixed'_
                'value' => 49_000,
                'min_order_total' => 2_000_000,
                'usage_limit' => null,
                'per_user_limit' => 3,
                'used_count' => 742,
                'starts_at' => now()->subDays(90),
                'expires_at' => now()->addDays(120),
                'is_active' => true,
            ],
            [
                'code' => 'NOWRUZ',
                'title' => 'جشنواره نوروزی — ۲۰٪ تخفیف',
                'type' => 'percent',
                'value' => 20,
                'max_discount' => 12_000_000,
                'min_order_total' => 15_000_000,
                'usage_limit' => 300,
                'per_user_limit' => 1,
                'used_count' => 300,
                'starts_at' => now()->subMonths(6),
                'expires_at' => now()->subMonths(5),
                'is_active' => false,
            ],
            [
                'code' => 'HOME25',
                'title' => '۲۵۰ هزار تومان تخفیف لوازم خانگی',
                'type' => 'fixed'_
                'value' => 250_000,
                'min_order_total' => 25_000_000,
                'usage_limit' => 400,
                'per_user_limit' => 2,
                'used_count' => 51,
                'starts_at' => now()->subDays(5),
                'expires_at' => now()->addDays(45),
                'is_active' => true,
            ],
            [
                'code' => 'BOOKLOVER',
                'title' => '۱۲٪ تخفیف کتاب و لوازم‌التحریر',
                'type' => 'percent',
                'value' => 12,
                'max_discount' => 1_000_000,
                'min_order_total' => 500_000,
                'usage_limit' => 1000,
                'per_user_limit' => 2,
                'used_count' => 187,
                'starts_at' => now()->subDays(20),
                'expires_at' => now()->addDays(80),
                'is_active' => true,
            ],
        ];

        foreach ($rows as $row) {
            Coupon::create($row);
        }
    }
}
