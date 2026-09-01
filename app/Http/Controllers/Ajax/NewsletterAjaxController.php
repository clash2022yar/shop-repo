<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class NewsletterAjaxController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ], [
            'email.required' => 'ایمیل خود را وارد کنید.',
            'email.email' => 'ایمیل واردشده معتبر نیست.',
        ]);

        $key = 'newsletter|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return $this->fail('تعداد درخواست‌ها زیاد است. کمی بعد دوباره تلاش کنید.', [], 429);
        }

        RateLimiter::hit($key, 600);

        if (Subscriber::where('email', $data['email'])->exists()) {
            return $this->fail('این ایمیل قبلاً در خبرنامه ثبت شده است.');
        }

        Subscriber::create(['email' => $data['email'], 'ip' => $request->ip()]);

        return $this->ok('ایمیل شما در خبرنامه دیجی‌نو ثبت شد.');
    }

    /**
     * The contact form creates a real support ticket so nothing is lost.
     * Guests get a lightweight account-less ticket attached to the shared
     * "guest" contact record.
     */
    public function contact(Request $request)
    {
        $request->merge(['mobile' => en_number($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'mobile' => ['nullable', 'regex:/^09\d{9}$/'],
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'name.required' => 'نام خود را وارد کنید.',
            'email.required' => 'ایمیل خود را وارد کنید.',
            'email.email' => 'ایمیل واردشده معتبر نیست.',
            'mobile.regex' => 'شماره موبایل معتبر نیست.',
            'subject.required' => 'موضوع پیام را وارد کنید.',
            'body.required' => 'متن پیام را بنویسید.',
            'body.min' => 'متن پیام باید حداقل ۱۰ نویسه باشد.',
        ]);

        $key = 'contact|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return $this->fail('تعداد پیام‌ها زیاد است. کمی بعد دوباره تلاش کنید.', [], 429);
        }

        RateLimiter::hit($key, 900);

        $user = $request->user() ?? User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'mobile' => $data['mobile'] ?? null,
                'password' => bin2hex(random_bytes(12)),
                'is_active' => true,
            ]
        );

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => $data['subject'],
            'department' => 'support',
            'priority' => 'normal',
            'status' => 'open',
        ]);

        $ticket->messages()->create(['user_id' => $user->id, 'body' => $data['body']]);

        return $this->ok('پیام شما ثبت شد. کارشناسان دیجی‌نو به‌زودی پاسخ می‌دهند. کد پیگیری: '.fa_number($ticket->code));
    }
}
