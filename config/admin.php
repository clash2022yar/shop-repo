<?php

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
use App\Models\Subscriber;
use App\Models\Ticket;
use App\Models\User;

return [
    'categories' => ['title' => 'دسته‌بندی‌ها', 'model' => Category::class, 'icon' => 'grid', 'fields' => ['name' => ['عنوان', 'text', 'required|string|max:100'], 'slug' => ['شناسه انگلیسی', 'text', 'required|alpha_dash|max:100'], 'image' => ['تصویر', 'image', 'nullable|string|max:500'], 'icon' => ['آیکون', 'text', 'required|in:phone,laptop,headphones,watch,game,tablet,charger,camera,monitor,box']]],
    'coupons' => ['title' => 'کدهای تخفیف', 'model' => Coupon::class, 'icon' => 'ticket', 'fields' => ['code' => ['کد تخفیف', 'text', 'required|alpha_dash|max:40'], 'percent' => ['درصد تخفیف', 'number', 'required|integer|between:1,90'], 'min_total' => ['حداقل سبد (تومان)', 'number', 'required|integer|min:0|max:10000000000'], 'usage_limit' => ['حداکثر استفاده', 'number', 'required|integer|min:1|max:1000000'], 'expires_at' => ['تاریخ انقضا', 'date', 'required|date'], 'active' => ['فعال', 'checkbox', 'boolean']]],
    'reviews' => ['title' => 'دیدگاه‌ها', 'model' => Review::class, 'icon' => 'message', 'readonly' => true, 'fields' => ['approved' => ['تأیید نمایش', 'checkbox', 'boolean']]],
    'inventory' => ['title' => 'انبار و موجودی', 'model' => Product::class, 'icon' => 'box', 'readonly' => true, 'fields' => ['stock' => ['موجودی کالا', 'number', 'required|integer|min:0|max:1000000']]],
    'banners' => ['title' => 'بنرها', 'model' => Banner::class, 'icon' => 'image', 'fields' => ['title' => ['عنوان', 'text', 'required|string|max:100'], 'subtitle' => ['زیرعنوان', 'text', 'required|string|max:200'], 'image' => ['تصویر', 'image', 'required|string|max:500'], 'link' => ['پیوند داخلی', 'text', 'required|string|max:300|regex:~^/(?!/)[a-zA-Z0-9/\-?=&_%]*$~'], 'color' => ['رنگ زمینه', 'color', 'required|regex:/^#[a-fA-F0-9]{6}$/'], 'active' => ['فعال', 'checkbox', 'boolean']]],
    'articles' => ['title' => 'مجله دیجینو', 'model' => Article::class, 'icon' => 'book', 'fields' => ['title' => ['عنوان', 'text', 'required|string|max:180'], 'slug' => ['شناسه انگلیسی', 'text', 'required|alpha_dash|max:180'], 'image' => ['تصویر', 'image', 'required|string|max:500'], 'excerpt' => ['خلاصه', 'textarea', 'required|string|max:500'], 'body' => ['متن مقاله', 'textarea', 'required|string|max:50000'], 'published' => ['منتشر شود', 'checkbox', 'boolean']]],
    'tickets' => ['title' => 'پشتیبانی', 'model' => Ticket::class, 'icon' => 'headphones', 'readonly' => true, 'fields' => ['reply' => ['پاسخ به کاربر', 'textarea', 'required|string|min:3|max:5000'], 'status' => ['وضعیت', 'select:open,answered,closed', 'required|in:open,answered,closed']]],
    'customers' => ['title' => 'مشتریان', 'model' => User::class, 'icon' => 'users', 'readonly' => true, 'fields' => []],
    'subscribers' => ['title' => 'اعضای خبرنامه', 'model' => Subscriber::class, 'icon' => 'mail', 'readonly' => true, 'fields' => []],
];
