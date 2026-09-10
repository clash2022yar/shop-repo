<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [['موبایل', 'mobile', 'iphone.jpg', 'phone'], ['لپ‌تاپ', 'laptop', 'laptop.jpg', 'laptop'], ['هدفون و هندزفری', 'audio', 'headphones.jpg', 'headphones'], ['ساعت هوشمند', 'watch', 'watch.jpg', 'watch'], ['کنسول بازی', 'gaming', 'ps5.jpg', 'game'], ['تبلت', 'tablet', 'tablet.jpg', 'tablet'], ['لوازم جانبی', 'accessories', 'charger.jpg', 'charger'], ['دوربین', 'camera', 'camera.jpg', 'camera'], ['مانیتور', 'monitor', 'monitor.jpg', 'monitor'], ['قطعات کامپیوتر', 'components', 'graphics.jpg', 'box']];
        foreach ($categories as [$name,$slug,$image,$icon]) {
            Category::updateOrCreate(['slug' => $slug], compact('name', 'icon') + ['image' => '/assets/images/'.$image]);
        }
        $base = [
            ['گوشی موبایل سامسونگ Galaxy S24 Ultra', 'galaxy-s24-ultra', 'Samsung', 'mobile', 56900000, 64900000, 'samsung.png', 'تیتانیوم بنفش', ['حافظه داخلی' => '۵۱۲ گیگابایت', 'حافظه RAM' => '۱۲ گیگابایت', 'دوربین اصلی' => '۲۰۰ مگاپیکسل', 'نمایشگر' => '۶٫۸ اینچ', 'باتری' => '۵۰۰۰ میلی‌آمپر ساعت']],
            ['لپ‌تاپ اپل MacBook Air M2', 'macbook-air-m2', 'Apple', 'laptop', 46500000, 49900000, 'laptop.jpg', 'میدنایت', ['پردازنده' => 'Apple M2', 'حافظه RAM' => '۸ گیگابایت', 'حافظه SSD' => '۵۱۲ گیگابایت', 'نمایشگر' => '۱۳٫۶ اینچ']],
            ['هدفون بی‌سیم سونی WH-1000XM5', 'sony-wh1000xm5', 'Sony', 'audio', 15900000, 17900000, 'headphones.jpg', 'مشکی', ['نوع اتصال' => 'بلوتوث و کابل', 'حذف نویز' => 'فعال', 'عمر باتری' => 'تا ۳۰ ساعت', 'وزن' => '۲۵۰ گرم']],
            ['ایرپاد پرو اپل نسل دوم USB-C', 'airpods-pro-2', 'Apple', 'audio', 11490000, 12990000, 'airpods.jpg', 'سفید', ['نوع اتصال' => 'بلوتوث', 'درگاه کیس' => 'USB-C', 'حذف نویز' => 'فعال', 'مقاومت' => 'IP54']],
            ['ساعت هوشمند اپل Watch Series 9', 'apple-watch-9', 'Apple', 'watch', 23500000, 25900000, 'watch.jpg', 'میدنایت', ['اندازه بدنه' => '۴۵ میلی‌متر', 'پردازنده' => 'S9', 'مقاومت در آب' => '۵۰ متر', 'حسگر' => 'ضربان قلب']],
            ['کنسول بازی سونی PlayStation 5 Slim', 'playstation-5-slim', 'Sony', 'gaming', 29900000, 32900000, 'ps5.jpg', 'سفید', ['حافظه SSD' => '۱ ترابایت', 'حافظه RAM' => '۱۶ گیگابایت', 'وضوح تصویر' => '4K', 'درایو' => 'دیسک بلوری']],
            ['گوشی اپل iPhone 15 Pro Max', 'iphone-15-pro-max', 'Apple', 'mobile', 72900000, 78900000, 'iphone.jpg', 'تیتانیوم طبیعی', ['حافظه داخلی' => '۲۵۶ گیگابایت', 'پردازنده' => 'A17 Pro', 'دوربین اصلی' => '۴۸ مگاپیکسل', 'نمایشگر' => '۶٫۷ اینچ']],
            ['تبلت سامسونگ Galaxy Tab S9 FE Plus', 'galaxy-tab-s9-fe', 'Samsung', 'tablet', 18900000, 20900000, 'tablet.jpg', 'خاکستری', ['حافظه داخلی' => '۱۲۸ گیگابایت', 'حافظه RAM' => '۸ گیگابایت', 'نمایشگر' => '۱۲٫۴ اینچ', 'قلم' => 'S Pen']],
            ['اسپیکر قابل حمل JBL Charge 5', 'jbl-charge-5', 'JBL', 'audio', 8900000, 9900000, 'speaker.jpg', 'خاکستری', ['نوع اتصال' => 'بلوتوث', 'عمر باتری' => 'تا ۲۰ ساعت', 'مقاومت' => 'IP67', 'خروجی' => 'USB']],
            ['ماوس بی‌سیم لاجیتک MX Master 3S', 'mx-master-3s', 'Logitech', 'accessories', 4950000, 5490000, 'mouse.jpg', 'گرافیت', ['دقت حسگر' => '۸۰۰۰ DPI', 'اتصال' => 'بلوتوث و USB', 'تعداد دکمه' => '۷ عدد', 'شارژ' => 'USB-C']],
            ['دوربین دیجیتال کانن EOS 2000D', 'canon-eos-2000d', 'Canon', 'camera', 23800000, 25900000, 'camera.jpg', 'مشکی', ['دقت حسگر' => '۲۴٫۱ مگاپیکسل', 'نوع حسگر' => 'APS-C', 'فیلم‌برداری' => 'Full HD', 'لنز' => '۱۸-۵۵ میلی‌متر']],
            ['شارژر دیواری انکر PowerPort III 65W', 'anker-powerport-65w', 'Anker', 'accessories', 2490000, 2790000, 'charger.jpg', 'سفید', ['توان خروجی' => '۶۵ وات', 'درگاه' => 'USB-C', 'فناوری' => 'PowerIQ 3.0', 'نوع' => 'شارژر دیواری']],
            ['مانیتور ال‌جی 24MR400-B', 'lg-24mr400', 'LG', 'monitor', 7900000, 8490000, 'monitor.jpg', 'مشکی', ['اندازه' => '۲۴ اینچ', 'پنل' => 'IPS', 'نرخ نوسازی' => '۱۰۰ هرتز', 'وضوح' => 'Full HD']],
            ['کیبورد مکانیکی ردراگون K552', 'redragon-k552', 'Redragon', 'accessories', 2890000, 3190000, 'keyboard.jpg', 'مشکی', ['اتصال' => 'USB', 'نوع سوییچ' => 'مکانیکی', 'نورپردازی' => 'RGB', 'چیدمان' => '۸۷ کلید']],
            ['کارت گرافیک ایسوس DUAL RTX 4060', 'asus-rtx-4060', 'ASUS', 'components', 18900000, 20900000, 'graphics.jpg', 'مشکی', ['حافظه' => '۸ گیگابایت', 'نوع حافظه' => 'GDDR6', 'رابط' => 'PCI Express 4.0', 'خنک‌کننده' => 'دو فن']],
        ];
        foreach ($base as $index => [$name,$slug,$brand,$cat,$price,$old,$image,$color,$specs]) {
            $category = Category::where('slug', $cat)->first();
            Product::updateOrCreate(['slug' => $slug], ['name' => $name, 'brand' => $brand, 'category_id' => $category->id, 'sku' => 'DG-'.str_pad($index + 1, 5, '0', STR_PAD_LEFT), 'price' => $price, 'old_price' => $old, 'stock' => 18 + $index * 3, 'image' => '/assets/images/'.$image, 'color' => $color, 'specs' => $specs, 'description' => "{$name}؛ انتخابی برای تجربهٔ بهتر در دنیای دیجیتال.\nپیش از خرید، مشخصات فنی، رنگ و نوع اتصال این مدل را بررسی کنید تا با نیاز شما هماهنگ باشد. اطلاعات کلیدی این محصول در جدول مشخصات درج شده است.\nکالا پیش از ارسال از نظر سلامت بسته‌بندی کنترل می‌شود. شرایط ارسال و بازگشت در صفحه راهنمای خرید قابل مطالعه است.", 'featured' => $index < 6, 'active' => true]);
        }
        // Sample bundles are explicit, not misleading duplicate hardware variants.
        foreach (range(1, 5) as $bundle) {
            foreach ($base as $index => [$name,$slug,$brand,$cat,$price,$old,$image,$color,$specs]) {
                $quantity = $bundle + 1;
                $bundleName = $name.' ـ بسته '.strtr((string) $quantity, ['2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶']).' عددی';
                Product::updateOrCreate(['slug' => $slug.'-pack-'.$quantity], ['name' => $bundleName, 'brand' => $brand, 'category_id' => Category::where('slug', $cat)->value('id'), 'sku' => 'DG-P'.$quantity.'-'.str_pad($index + 1, 4, '0', STR_PAD_LEFT), 'price' => $price * $quantity, 'old_price' => $old * $quantity, 'stock' => ($index + $bundle) % 12, 'image' => '/assets/images/'.$image, 'color' => $color, 'specs' => ['تعداد در بسته' => $quantity.' عدد'] + $specs, 'description' => "این محصول یک بسته شامل {$quantity} عدد از {$name} است. قیمت نمایش‌داده‌شده مربوط به کل بسته است، نه یک دستگاه.\nبرای سفارش‌های گروهی مناسب است. مشخصات هر دستگاه در جدول مشخصات آمده است.", 'featured' => false, 'active' => true]);
            }
        }
        Banner::updateOrCreate(['title' => 'ساعت‌های هوشمند'], ['subtitle' => 'هر لحظه، یک قدم جلوتر', 'image' => '/assets/images/watch.jpg', 'link' => '/category/watch', 'color' => '#edf7f5', 'active' => true]);
        Banner::updateOrCreate(['title' => 'صدایی فراتر از انتظار'], ['subtitle' => 'هدفون‌های منتخب، با قیمت ویژه', 'image' => '/assets/images/headphones.jpg', 'link' => '/category/audio?sale=1', 'color' => '#fff0f2', 'active' => true]);
        $articles = [
            ['راهنمای انتخاب گوشی؛ کدام مدل مناسب شماست؟', 'smartphone-buying-guide', 'journal-phone.jpg', 'از نمایشگر تا دوربین؛ قبل از خرید گوشی جدید، این نکته‌های کاربردی را بدانید.', 'برای انتخاب گوشی، پیش از مقایسه اعداد، نیاز روزانه خود را مشخص کنید. اگر عکاسی اولویت شماست، علاوه بر تعداد مگاپیکسل به اندازه حسگر، لرزش‌گیر و کیفیت پردازش تصویر توجه کنید.\n\nحافظه داخلی با حافظه موقت متفاوت است. اگر ویدئوهای زیادی نگه می‌دارید یا بازی نصب می‌کنید، ظرفیت ذخیره‌سازی بالاتر انتخاب مناسب‌تری است. امکان افزایش حافظه را در مشخصات هر مدل بررسی کنید.\n\nاندازه نمایشگر را متناسب با راحتی دست خود انتخاب کنید. وضوح و روشنایی صفحه به‌اندازه نرخ نوسازی مهم هستند. دوام باتری تنها به ظرفیت آن وابسته نیست و پردازنده، نرم‌افزار و نوع استفاده هم مؤثرند.\n\nپیش از ثبت سفارش، وضعیت رجیستری، شرایط گارانتی و لوازم داخل جعبه را از فروشنده بررسی کنید. بودجه خود را فقط به قیمت گوشی محدود نکنید؛ هزینه شارژر و محافظ نیز ممکن است به خرید اضافه شود.'],
            ['مک‌بوک ایر M2؛ سبک برای کارهای بزرگ', 'macbook-air-guide', 'journal-laptop.jpg', 'یک لپ‌تاپ سبک برای کار، یادگیری و خلاقیت؛ با نقاط قوت و محدودیت‌های آن آشنا شوید.', 'مک‌بوک ایر با تراشه M2 برای استفاده روزمره، برنامه‌نویسی و کارهای خلاقانه سبک طراحی شده است. بدنه بدون فن آن در استفاده عادی بی‌صداست، اما برای پردازش‌های سنگین مداوم بهتر است مدل‌های دارای خنک‌کننده فعال را هم بررسی کنید.\n\nحافظه RAM و SSD پس از خرید به‌سادگی قابل ارتقا نیستند. بنابراین هنگام انتخاب پیکربندی، نیاز چند سال آینده خود را در نظر بگیرید. برای اجرای هم‌زمان ابزارهای توسعه و نرم‌افزارهای سنگین، حافظه بیشتر اهمیت دارد.\n\nسازگاری برنامه‌های تخصصی خود را با macOS و Apple Silicon بررسی کنید. وجود یک نرم‌افزار در ویندوز به معنی در دسترس بودن همه امکانات آن در مک نیست.\n\nپورت‌ها و پشتیبانی از نمایشگر خارجی نیز مهم‌اند. تعداد نمایشگرهای قابل اتصال را در مشخصات رسمی مدل بررسی کنید و در صورت نیاز هزینه هاب را در بودجه قرار دهید.'],
            ['هدفون یا هندزفری؟ صدای مناسب زندگی شما', 'headphone-buying-guide', 'journal-audio.jpg', 'حذف نویز، راحتی و شارژدهی؛ سه نکته‌ای که انتخاب هدفون را ساده‌تر می‌کنند.', 'اولین تفاوت هدفون و هندزفری در نحوه قرارگیری است. هدفون دورگوشی معمولاً برای استفاده طولانی پشت میز مناسب است، اما هندزفری وزن و فضای کمتری دارد و برای رفت‌وآمد راحت‌تر است.\n\nحذف نویز فعال به کاهش صداهای پیوسته محیط کمک می‌کند، ولی همه صداها را به‌طور کامل حذف نمی‌کند. کیفیت میکروفون نیز موضوعی جدا از کیفیت صدای پخش است؛ اگر تماس زیاد دارید، نمونه صدای میکروفون را بررسی کنید.\n\nشارژدهی اعلام‌شده معمولاً در شرایط مشخص آزمایشگاهی اندازه‌گیری می‌شود. فعال بودن حذف نویز، شدت صدا و نوع اتصال بر مدت استفاده تأثیر می‌گذارند.\n\nبرای ورزش به مقاومت در برابر رطوبت و ثابت ماندن محصول توجه کنید. درجه IP و شرایط گارانتی را بخوانید؛ مقاومت در برابر پاشش آب به معنی مناسب بودن برای شنا نیست.'],
        ];
        foreach ($articles as [$title,$slug,$image,$excerpt,$body]) {
            Article::updateOrCreate(['slug' => $slug], ['title' => $title, 'image' => '/assets/images/'.$image, 'excerpt' => $excerpt, 'body' => str_replace('\\n', "\n", $body), 'published' => true]);
        }
        foreach (['shop_name' => 'دیجینو', 'support_email' => '', 'support_phone' => '', 'address' => '', 'shipping_cost' => '65000', 'free_shipping' => '10000000'] as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
        if (config('shop.demo')) {
            $password = env('DEMO_ADMIN_PASSWORD');
            if ($password) {
                $admin = User::firstOrCreate(['email' => env('DEMO_ADMIN_EMAIL', 'admin@digino.test')], ['name' => 'مدیر دیجینو', 'password' => $password]);
                $admin->forceFill(['is_admin' => true])->save();
            }
            Coupon::firstOrCreate(['code' => 'DIGINO10'], ['percent' => 10, 'min_total' => 1000000, 'usage_limit' => 100, 'expires_at' => now()->addMonths(2), 'active' => true]);
        }
    }
}
