<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\SettingController;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            'site_name' => 'دیجی‌نو',
            'site_tagline' => 'خرید هوشمند کالای دیجیتال',
            'site_description' => 'دیجی‌نو فروشگاه اینترنتی کالای دیجیتال، لوازم خانگی، مد و پوشاک و کالای سوپرمارکتی با ضمانت اصالت و ارسال سریع به سراسر ایران است.',
            'support_phone' => '021-91008080',
            'support_email' => 'support@digino.example',
            'address' => 'تهران، خیابان ولیعصر، بالاتر از میدان ونک، برج دیجی‌نو، طبقه ۹',
            'working_hours' => 'شنبه تا چهارشنبه ۹ تا ۱۸ — پنجشنبه ۹ تا ۱۳',

            'free_shipping_from' => (string) config('digino.checkout.free_shipping_from'),
            'shipping_cost' => (string) config('digino.checkout.default_shipping_cost'),
            'low_stock_threshold' => (string) config('digino.catalog.low_stock_threshold'),
            'max_cart_qty' => (string) config('digino.cart.max_qty_per_item'),
            'guest_checkout' => '0',
            'auto_approve_reviews' => '0',

            'instagram' => 'https://instagram.com/digino',
            'telegram' => 'https://t.me/digino',
            'linkedin' => 'https://linkedin.com/company/digino',
            'twitter' => 'https://x.com/digino',

            'maintenance_mode' => '0',
            'maintenance_message' => 'دیجی‌نو در حال به‌روزرسانی است. تا دقایقی دیگر باز می‌گردیم.',
        ];

        foreach (SettingController::SCHEMA as $group => $fields) {
            foreach ($fields as $key => $meta) {
                Setting::updateOrCreate(['key' => $key], [
                    'value' => $values[$key] ?? null,
                    'group' => $group,
                    'type' => $meta['type'],
                    'label' => $meta['label'],
                ]);
            }
        }
    }
}
