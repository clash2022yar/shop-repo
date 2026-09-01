<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\SearchLog;
use App\Models\Subscriber;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    public function run(): void
    {
        $this->tickets();
        $this->wishlists();
        $this->searchLogs();
        $this->subscribers();
        $this->activity();
    }

    protected function tickets(): void
    {
        $customers = User::customers()->inRandomOrder()->limit(30)->get();
        $staff = User::staff()->get();

        if ($customers->isEmpty()) {
            return;
        }

        $subjects = [
            ['پیگیری وضعیت ارسال سفارش', 'سلام، سه روز از ثبت سفارشم می‌گذرد و هنوز کد رهگیری برایم ارسال نشده است. ممکن است وضعیت را بررسی کنید؟'],
            ['درخواست مرجوعی کالا', 'کالای دریافتی با تصویر سایت تفاوت رنگ دارد. مایلم درخواست مرجوعی ثبت کنم.'],
            ['مشکل در پرداخت اینترنتی', 'مبلغ از حسابم کسر شد اما سفارش ثبت نشد. لطفاً بررسی بفرمایید.'],
            ['سؤال درباره گارانتی', 'گارانتی این محصول توسط کدام شرکت ارائه می‌شود و شامل چه مواردی است؟'],
            ['اصلاح آدرس سفارش', 'در ثبت آدرس اشتباه کردم، امکان اصلاح پیش از ارسال وجود دارد؟'],
            ['درخواست فاکتور رسمی', 'برای سفارشم فاکتور رسمی با مشخصات شرکت نیاز دارم.'],
        ];

        $replies = [
            'با سلام و احترام؛ درخواست شما ثبت شد و همکاران واحد مربوطه در حال بررسی هستند. نتیجه حداکثر تا ۲۴ ساعت آینده از همین طریق اطلاع‌رسانی می‌شود.',
            'با سلام؛ ضمن پوزش بابت تأخیر پیش‌آمده، سفارش شما در مرحله آماده‌سازی است و کد رهگیری امروز برایتان پیامک خواهد شد.',
            'با سلام؛ مبلغ کسرشده در تراکنش‌های ناموفق حداکثر ظرف ۷۲ ساعت کاری به‌صورت خودکار به حساب شما بازمی‌گردد.',
            'با سلام؛ درخواست مرجوعی شما تأیید شد. لطفاً کالا را در بسته‌بندی اصلی آماده کنید تا پیک برای دریافت مراجعه کند.',
        ];

        foreach ($customers as $i => $customer) {
            [$subject, $body] = $subjects[array_rand($subjects)];
            $status = collect(['open', 'answered', 'answered', 'closed'])->random();
            $createdAt = now()->subDays(random_int(0, 60))->subHours(random_int(0, 20));

            $ticket = Ticket::create([
                'code' => 'TK-'.str_pad((string) ($i + 1001), 5, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'order_id' => Order::where('user_id', $customer->id)->value('id'),
                'subject' => $subject,
                'department' => collect(['support', 'orders', 'technical', 'finance'])->random(),
                'priority' => collect(['low', 'normal', 'normal', 'high'])->random(),
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $ticket->messages()->create([
                'user_id' => $customer->id,
                'body' => $body,
                'is_staff' => false,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            if ($status !== 'open' && $staff->isNotEmpty()) {
                $answeredAt = $createdAt->copy()->addHours(random_int(1, 20));

                $ticket->messages()->create([
                    'user_id' => $staff->random()->id,
                    'body' => collect($replies)->random(),
                    'is_staff' => true,
                    'created_at' => $answeredAt,
                    'updated_at' => $answeredAt,
                ]);

                $ticket->update(['updated_at' => $answeredAt]);
            }
        }
    }

    protected function wishlists(): void
    {
        $productIds = Product::pluck('id');
        $customers = User::customers()->inRandomOrder()->limit(60)->get();

        foreach ($customers as $customer) {
            foreach ($productIds->shuffle()->take(random_int(0, 8)) as $productId) {
                Wishlist::firstOrCreate([
                    'user_id' => $customer->id,
                    'product_id' => $productId,
                ]);
            }
        }
    }

    protected function searchLogs(): void
    {
        $terms = [
            'گوشی سامسونگ', 'آیفون ۱۵', 'لپ تاپ ایسوس', 'هدفون بی سیم', 'ساعت هوشمند',
            'ماشین لباسشویی', 'جاروبرقی', 'کفش نایک', 'عطر مردانه', 'کرم ضد آفتاب',
            'پاوربانک', 'کارت حافظه', 'مانیتور گیمینگ', 'کنسول پلی استیشن', 'کتاب رمان',
            'شارژر سریع', 'تبلت سامسونگ', 'یخچال ساید', 'دوربین کانن', 'ماوس بی سیم',
        ];

        $userIds = User::customers()->pluck('id');

        foreach ($terms as $term) {
            foreach (range(1, random_int(3, 40)) as $n) {
                SearchLog::create([
                    'term' => $term,
                    'results_count' => random_int(0, 60),
                    'user_id' => random_int(1, 100) <= 60 ? $userIds->random() : null,
                    'created_at' => now()->subDays(random_int(0, 45))->subMinutes(random_int(0, 1400)),
                ]);
            }
        }
    }

    protected function subscribers(): void
    {
        for ($i = 1; $i <= 60; $i++) {
            Subscriber::create([
                'email' => 'subscriber'.$i.'@example.com',
                'is_active' => random_int(1, 100) <= 92,
                'ip' => '5.'.random_int(50, 250).'.'.random_int(1, 250).'.'.random_int(1, 250),
                'created_at' => now()->subDays(random_int(1, 300)),
            ]);
        }
    }

    protected function activity(): void
    {
        $staff = User::staff()->get();
        $products = Product::inRandomOrder()->limit(30)->get();
        $orders = Order::inRandomOrder()->limit(30)->get();

        if ($staff->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            ActivityLog::create([
                'user_id' => $staff->random()->id,
                'action' => collect(['product.created', 'product.updated', 'product.stock'])->random(),
                'subject_type' => Product::class,
                'subject_id' => $product->id,
                'description' => 'کالای «'.$product->name.'» به‌روزرسانی شد.',
                'ip' => '192.168.'.random_int(0, 20).'.'.random_int(2, 250),
                'created_at' => now()->subDays(random_int(0, 40))->subMinutes(random_int(0, 1400)),
            ]);
        }

        foreach ($orders as $order) {
            ActivityLog::create([
                'user_id' => $staff->random()->id,
                'action' => 'order.status',
                'subject_type' => Order::class,
                'subject_id' => $order->id,
                'description' => 'وضعیت سفارش '.$order->code.' تغییر کرد.',
                'ip' => '192.168.'.random_int(0, 20).'.'.random_int(2, 250),
                'created_at' => now()->subDays(random_int(0, 40))->subMinutes(random_int(0, 1400)),
            ]);
        }
    }
}
