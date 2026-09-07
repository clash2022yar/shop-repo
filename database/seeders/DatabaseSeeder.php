<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create(['name' => 'مدیر دیجینو', 'email' => 'admin@digino.ir', 'phone' => '09120000001', 'password' => Hash::make('password'), 'is_admin' => true]);
        $user = User::create(['name' => 'علی محمدی', 'email' => 'user@digino.ir', 'phone' => '09120000002', 'password' => Hash::make('password')]);
        Address::create(['user_id' => $user->id, 'title' => 'خانه', 'recipient' => 'علی محمدی', 'phone' => '09120000002', 'province' => 'تهران', 'city' => 'تهران', 'address' => 'خیابان ولیعصر، کوچه دیجینو، پلاک ۱۲۳', 'postal_code' => '1599912345', 'is_default' => true]);
        $cats = [];
        foreach ([['کالای دیجیتال', 'digital'], ['موبایل', 'mobile'], ['لپ‌تاپ و تبلت', 'laptop'], ['صوتی و تصویری', 'audio'], ['ساعت هوشمند', 'watch'], ['گیمینگ', 'gaming'], ['دوربین', 'camera'], ['لوازم جانبی', 'accessory']] as $c) {
            $cats[$c[1]] = Category::create(['name' => $c[0], 'slug' => $c[1], 'description' => 'بهترین کالاهای '.$c[0]]);
        }
        $base = [
            ['گوشی موبایل سامسونگ Galaxy S24 Ultra', 'galaxy-s24', 'SG-S24', 56900000, 62900000, 18, 4.8, 124, 'سامسونگ', 'galaxy-s24.webp', 'mobile'],
            ['ایرپادز پرو ۲ اپل USB-C', 'airpods-pro-2', 'AP-PRO2', 11490000, 12900000, 34, 4.7, 88, 'اپل', 'airpods.webp', 'accessory'],
            ['لپ‌تاپ اپل MacBook Air M2 2023', 'macbook-air-m2', 'MB-M2', 46500000, 49900000, 12, 4.9, 74, 'اپل', 'macbook.webp', 'laptop'],
            ['ساعت هوشمند اپل Watch Series 9', 'apple-watch-9', 'AW-9', 23500000, 25900000, 21, 4.8, 67, 'اپل', 'apple-watch.webp', 'watch'],
            ['هدفون سونی WH-1000XM5', 'sony-wh1000xm5', 'SN-XM5', 15900000, 17500000, 16, 4.6, 65, 'سونی', 'sony-headphone.webp', 'audio'],
            ['کنسول بازی Sony PlayStation 5 Slim', 'playstation-5-slim', 'PS5-S', 29900000, 31900000, 8, 4.9, 118, 'سونی', 'playstation.webp', 'gaming'],
            ['تبلت سامسونگ Galaxy Tab S9 FE', 'galaxy-tab-s9', 'TAB-S9', 18900000, 20500000, 24, 4.5, 54, 'سامسونگ', 'galaxy-tab.webp', 'laptop'],
            ['اسپیکر بلوتوثی JBL Charge 5', 'jbl-charge-5', 'JBL-C5', 8900000, 9900000, 31, 4.6, 65, 'جی‌بی‌ال', 'jbl.webp', 'audio'],
            ['دوربین دیجیتال کانن EOS 2000D', 'canon-eos-2000d', 'CN-2000', 23800000, 25900000, 7, 4.5, 66, 'کانن', 'canon.webp', 'camera'],
            ['گوشی موبایل اپل iPhone 15 Pro Max', 'iphone-15-pro-max', 'IP-15PM', 72900000, 75900000, 14, 4.8, 89, 'اپل', 'iphone.webp', 'mobile'],
        ];
        $products = [];
        for ($i = 0; $i < 40; $i++) {
            $p = $base[$i % count($base)];
            $suffix = $i < 10 ? '' : ' - مدل '.($i + 1);
            $products[] = Product::create(['category_id' => $cats[$p[10]]->id, 'name' => $p[0].$suffix, 'slug' => $p[1].'-'.($i + 1), 'sku' => $p[2].'-'.($i + 1), 'short_description' => 'کالای اصل با ضمانت معتبر و ارسال سریع دیجینو', 'description' => 'این محصول با تضمین اصالت کالا، بهترین قیمت و هفت روز ضمانت بازگشت در دیجینو عرضه می‌شود. تجربه‌ای مطمئن از خرید آنلاین با پشتیبانی حرفه‌ای.', 'price' => $p[3] + ($i > 9 ? ($i % 4) * 350000 : 0), 'old_price' => $p[4] + ($i > 9 ? ($i % 4) * 350000 : 0), 'stock' => $p[5] + ($i % 9), 'rating' => $p[6], 'reviews_count' => $p[7] + $i, 'brand' => $p[8], 'image' => 'images/products/'.$p[9], 'gallery' => ['images/products/'.$p[9]], 'specifications' => ['گارانتی' => '۱۸ ماهه شرکتی', 'اصالت' => 'اصل', 'رنگ' => 'مشکی', 'ارسال' => 'سریع دیجینو'], 'is_featured' => $i < 12]);
        }
        Coupon::create(['code' => 'DIGINO10', 'type' => 'percent', 'value' => 10, 'min_order' => 1000000, 'usage_limit' => 100, 'expires_at' => now()->addMonths(3), 'is_active' => true]);
        Coupon::create(['code' => 'WELCOME', 'type' => 'fixed', 'value' => 500000, 'min_order' => 5000000, 'usage_limit' => 50, 'expires_at' => now()->addMonth(), 'is_active' => true]);
        Banner::create(['title' => 'جدیدترین کالای دیجیتال با بهترین قیمت', 'subtitle' => 'گوشی، لپ‌تاپ، ساعت هوشمند و بیشتر', 'url' => '/products', 'position' => 'home', 'sort_order' => 1]);
        foreach (array_slice($products, 0, 4) as $i => $p) {
            Review::create(['user_id' => $user->id, 'product_id' => $p->id, 'rating' => 5 - $i % 2, 'body' => 'کیفیت محصول و بسته‌بندی عالی بود و خیلی سریع به دستم رسید.', 'is_approved' => true]);
        }
        foreach (['delivered', 'shipped', 'processing', 'cancelled'] as $i => $status) {
            $p = $products[$i];
            $order = Order::create(['number' => 'DG-1406-'.str_pad($i + 21, 5, '0', STR_PAD_LEFT), 'user_id' => $user->id, 'status' => $status, 'subtotal' => $p->price, 'discount' => 0, 'shipping' => 0, 'total' => $p->price, 'payment_method' => 'online', 'payment_status' => 'paid', 'address' => ['recipient' => 'علی محمدی', 'phone' => '09120000002', 'city' => 'تهران', 'address' => 'خیابان ولیعصر، پلاک ۱۲۳']]);
            OrderItem::create(['order_id' => $order->id, 'product_id' => $p->id, 'product_name' => $p->name, 'product_image' => $p->image, 'quantity' => 1, 'price' => $p->price, 'total' => $p->price]);
        }
    }
}
