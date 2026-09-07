# دیجینو — فروشگاه هوشمند کالای دیجیتال

دیجینو یک فروشگاه کامل فارسی و راست‌به‌چپ بر پایه **Laravel 13** است. رابط کاربری با HTML، Blade، CSS و JavaScript خالص ساخته شده و هیچ وابستگی‌ای به React، Vue، Alpine یا Vite ندارد. عملیات سبد خرید، ورود، ثبت‌نام، تسویه‌حساب، دیدگاه، علاقه‌مندی و مدیریت داده‌ها با Fetch/AJAX و بدون بارگذاری مجدد صفحه انجام می‌شود.

## فناوری و پیش‌نیاز

- PHP دقیق هدف پروژه: `8.3.28` (در `composer.json` و `config.platform` قفل شده است)
- Laravel `13.0.0`
- SQLite (پیش‌فرض؛ قابل تغییر به MySQL در `.env`)
- Composer 2
- JavaScript خالص و CSS خروجی آماده؛ بدون Node.js و Vite

## نصب

```bash
cp .env.example .env
composer install
php artisan key:generate
mkdir -p database && touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0
```

یا:

```bash
composer setup
```

## حساب‌های آماده توسعه

| نقش | ایمیل | رمز |
|---|---|---|
| مدیر | `admin@digino.ir` | `password` |
| مشتری | `user@digino.ir` | `password` |

این حساب‌ها فقط توسط Seeder محیط توسعه ساخته می‌شوند. در استقرار واقعی رمزها را تغییر دهید و اجرای Seeder نمونه را غیرفعال کنید.

## صفحات فروشگاه

- خانه، فروشگاه/محصولات، دسته‌بندی و نتایج جستجو
- جزئیات محصول، سبد خرید و تسویه‌حساب واقعی با پرداخت در محل
- ورود/ثبت‌نام
- داشبورد مشتری، سفارش‌ها، جزئیات سفارش، علاقه‌مندی و ویرایش پروفایل
- درباره ما، شرایط استفاده، حقوق اثر و حریم خصوصی

## پنل مدیریت

- داشبورد آماری
- محصولات، افزودن، ویرایش و حذف
- دسته‌بندی‌ها
- سفارش‌ها و تغییر چرخه وضعیت
- مشتریان
- کدهای تخفیف
- تأیید دیدگاه‌ها
- ویرایش سریع موجودی انبار
- بنرها
- تنظیمات پایدار فروشگاه

## معماری

- اجزای مشترک: `resources/views/partials`
- چیدمان فروشگاه و مدیریت: `resources/views/layouts`
- کنترلرها: `app/Http/Controllers`
- مدل‌ها: `app/Models`
- میان‌افزار مدیریت: `app/Http/Middleware/AdminMiddleware.php`
- اسکریپت AJAX: `public/js/app.js`
- Design system و استایل responsive: `public/css/app.css`

## تصاویر و فونت

تصاویر محصول به‌صورت محلی در `public/images/products` نگهداری می‌شوند و در زمان اجرا هیچ Hotlink خارجی وجود ندارد. فهرست مبدأها در [ATTRIBUTION.md](ATTRIBUTION.md) آمده است. فونت Vazirmatn تحت مجوز SIL OFL استفاده شده است.

## حقوق اثر

طراحی و توسعه: **یارمحمدی**. این پروژه اختصاصی است و مجوز متن‌باز ندارد. شرایط کامل در [RIGHTS.md](RIGHTS.md) درج شده است.
