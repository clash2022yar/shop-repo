<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Digino maintenance commands
|--------------------------------------------------------------------------
*/

Artisan::command('digino:expire-orders', function () {
    // Unpaid orders older than 3 days release their stock again.
    $stale = Order::where('status', 'pending')
        ->where('created_at', '<', now()->subDays(3))
        ->get();

    $checkout = app(App\Services\CheckoutService::class);

    foreach ($stale as $order) {
        $checkout->cancel($order, 'لغو خودکار به دلیل عدم پرداخت.');
    }

    $this->info("{$stale->count()} سفارش پرداخت‌نشده لغو شد.");
})->purpose('Cancel unpaid orders older than three days');

Artisan::command('digino:refresh-ratings', function () {
    Product::query()->chunkById(200, function ($products) {
        $products->each->refreshRating();
    });

    $this->info('امتیاز همه محصولات بازمحاسبه شد.');
})->purpose('Recalculate product ratings from approved reviews');

Schedule::command('digino:expire-orders')->hourly();
Schedule::command('digino:refresh-ratings')->dailyAt('03:30');
Schedule::command('queue:prune-failed --hours=168')->daily();
