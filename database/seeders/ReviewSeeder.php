<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\ReviewStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Question;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    protected array $titles = [
        'کاملاً راضی هستم', 'ارزش خرید بالا', 'نسبت به قیمتش عالیه', 'انتظار بیشتری داشتم',
        'کیفیت ساخت خوب', 'بسته‌بندی و ارسال عالی', 'پیشنهاد می‌کنم', 'خوب ولی بی‌نقص نیست',
        'دقیقاً همون چیزی که می‌خواستم', 'برای استفاده روزمره مناسبه',
    ];

    protected array $bodies = [
        'بعد از حدود یک ماه استفاده می‌تونم بگم انتخاب درستی بوده. کیفیت ساخت در حد قیمتشه و تا الان هیچ مشکلی نداشتم. ارسال هم سریع‌تر از چیزی بود که فکر می‌کردم.',
        'کالا دقیقاً مطابق توضیحات سایت بود و سالم به دستم رسید. بسته‌بندی خیلی خوب انجام شده بود و از این بابت از تیم دیجی‌نو ممنونم.',
        'نسبت به مدل قبلی که داشتم پیشرفت محسوسی داره، مخصوصاً در مصرف انرژی. تنها ایرادی که می‌گیرم اینه که دفترچه راهنمای فارسی همراهش نبود.',
        'برای کاربری روزمره کاملاً جواب می‌ده اما اگر استفاده حرفه‌ای مدنظرتونه، پیشنهاد می‌کنم مدل بالاتر رو بررسی کنید.',
        'قیمتش در مقایسه با فروشگاه‌های دیگه منصفانه بود و با کد تخفیف هم خرید خوبی شد. پشتیبانی هم سریع جواب داد.',
        'کیفیت متریال خوبه ولی وزنش کمی بیشتر از چیزیه که در مشخصات نوشته شده به نظر می‌رسه. در مجموع راضی‌ام.',
        'دو هفته‌ست دارم استفاده می‌کنم و واقعاً توی کارهای روزمره کمکم کرده. برای هدیه دادن هم گزینه خوبیه.',
        'ارسال یک روز دیرتر از موعد انجام شد ولی خود کالا بی‌نقص بود و پشتیبانی هم پیگیری کرد.',
    ];

    protected array $pros = [
        'کیفیت ساخت', 'قیمت مناسب', 'بسته‌بندی خوب', 'ارسال سریع', 'طراحی زیبا',
        'عملکرد روان', 'باتری با دوام', 'سبک و قابل حمل',
    ];

    protected array $cons = [
        'نبود دفترچه فارسی', 'وزن نسبتاً زیاد', 'کمبود رنگ‌بندی', 'گرم شدن هنگام استفاده طولانی',
        'قیمت بالاتر از رقبا',
    ];

    protected array $questions = [
        'سلام، این کالا اصل و دارای گارانتی معتبر هست؟',
        'ارسال به شهرستان چند روز طول می‌کشه؟',
        'امکان پرداخت در محل برای این کالا وجود داره؟',
        'آیا این مدل با لوازم جانبی نسل قبل سازگاره؟',
        'وزن دقیق کالا همراه با بسته‌بندی چقدره؟',
        'اگر کالا مشکل داشته باشه امکان مرجوعی هست؟',
    ];

    protected array $answers = [
        'با سلام؛ بله، تمام کالاهای دیجی‌نو اصل بوده و با ضمانت اصالت و سلامت فیزیکی ارسال می‌شوند.',
        'با سلام؛ ارسال به مراکز استان‌ها معمولاً ۲ تا ۴ روز کاری و به سایر شهرها تا ۵ روز کاری زمان می‌برد.',
        'با سلام؛ پرداخت در محل برای این کالا فعال است، مشروط بر اینکه شهر مقصد تحت پوشش این سرویس باشد.',
        'با سلام؛ بله، سازگاری کامل دارد و در بخش مشخصات فنی جزئیات آن ذکر شده است.',
    ];

    public function run(): void
    {
        $products = Product::inRandomOrder()->limit(140)->get();
        $customers = User::customers()->inRandomOrder()->limit(80)->get();
        $staff = User::staff()->first();

        if ($products->isEmpty() || $customers->isEmpty()) {
            return;
        }

        $deliveredOrders = Order::where('status', OrderStatus::Delivered->value)->with('items')->get();

        foreach ($products as $product) {
            $count = random_int(0, 9);

            for ($i = 0; $i < $count; $i++) {
                $user = $customers->random();
                $rating = collect([5, 5, 5, 4, 4, 4, 3, 3, 2, 1])->random();

                $order = $deliveredOrders->first(
                    fn ($o) => $o->user_id === $user->id && $o->items->contains('product_id', $product->id)
                );

                $status = match (true) {
                    random_int(1, 100) <= 78 => ReviewStatus::Approved,
                    random_int(1, 100) <= 70 => ReviewStatus::Pending,
                    default => ReviewStatus::Rejected,
                };

                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'order_id' => $order?->id,
                    'title' => collect($this->titles)->random(),
                    'body' => collect($this->bodies)->random(),
                    'rating' => $rating,
                    'pros' => collect($this->pros)->shuffle()->take(random_int(1, 3))->values()->all(),
                    'cons' => $rating <= 3 ? collect($this->cons)->shuffle()->take(random_int(1, 2))->values()->all() : [],
                    'recommends' => $rating >= 4,
                    'status' => $status->value,
                    'reject_reason' => $status === ReviewStatus::Rejected ? 'متن دیدگاه با قوانین انتشار دیجی‌نو مطابقت نداشت.' : null,
                    'is_buyer' => (bool) $order,
                    'likes' => random_int(0, 140),
                    'dislikes' => random_int(0, 24),
                    'created_at' => now()->subDays(random_int(1, 200)),
                ]);
            }

            $product->refreshRating();

            // pre-sale questions
            for ($q = 0; $q < random_int(0, 3); $q++) {
                $question = Question::create([
                    'product_id' => $product->id,
                    'user_id' => $customers->random()->id,
                    'body' => collect($this->questions)->random(),
                    'status' => random_int(1, 100) <= 75 ? 'approved' : 'pending',
                    'created_at' => now()->subDays(random_int(1, 120)),
                ]);

                if ($question->status === 'approved' && $staff && random_int(1, 100) <= 80) {
                    $question->answers()->create([
                        'user_id' => $staff->id,
                        'body' => collect($this->answers)->random(),
                        'is_staff' => true,
                        'status' => 'approved',
                        'created_at' => $question->created_at->copy()->addHours(random_int(1, 20)),
                    ]);
                }
            }

            $product->update(['questions_count' => $product->questions()->count()]);
        }

        $this->command?->info('  › '.Review::count().' reviews and '.Question::count().' questions created.');
    }
}
