<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $steps = [
            'تنظیمات فروشگاه' => SettingSeeder::class,
            'دسته‌بندی‌ها، برندها و روش‌های ارسال' => CatalogSeeder::class,
            'کدهای تخفیف' => CouponSeeder::class,
            'کالاها' => ProductSeeder::class,
            'کاربران و نشانی‌ها' => UserSeeder::class,
            'سفارش‌ها' => OrderSeeder::class,
            'دیدگاه‌ها و پرسش‌ها' => ReviewSeeder::class,
            'بنرها، مجله و صفحات' => ContentSeeder::class,
            'تیکت‌ها و داده‌های تعاملی' => SupportSeeder::class,
        ];

        foreach ($steps as $label => $class) {
            $this->command?->info("→ {$label}");
            $this->call($class);
        }

        Model::reguard();

        $this->command?->newLine();
        $this->command?->info('دیجی‌نو آماده است. ورود مدیر: admin@digino.test / password');
    }
}
