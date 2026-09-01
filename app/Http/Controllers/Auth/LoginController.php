<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ], [
            'identifier.required' => 'شماره موبایل یا ایمیل خود را وارد کنید.',
            'password.required' => 'رمز عبور را وارد کنید.',
        ]);

        $throttleKey = Str::lower($data['identifier']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return $this->fail(
                'تلاش‌های ناموفق زیاد بود. لطفاً '.fa_number($seconds).' ثانیه دیگر دوباره تلاش کنید.',
                [],
                429
            );
        }

        $identifier = en_number(trim($data['identifier']));
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $guestSession = $request->session()->getId();

        if (! Auth::attempt([$field => $identifier, 'password' => $data['password']], (bool) ($data['remember'] ?? false))) {
            RateLimiter::hit($throttleKey, 300);

            return $this->fail('شماره موبایل/ایمیل یا رمز عبور نادرست است.', [
                'errors' => ['identifier' => ['اطلاعات ورود صحیح نیست.']],
            ]);
        }

        if (! Auth::user()->is_active) {
            Auth::logout();

            return $this->fail('حساب کاربری شما غیرفعال شده است. لطفاً با پشتیبانی تماس بگیرید.', [], 403);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        Auth::user()->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        // Whatever the visitor picked before signing in stays in their cart.
        $this->cart->mergeGuestCart(Auth::id(), $guestSession);

        return $this->ok('خوش آمدید!', [
            'redirect' => $this->redirectTarget($request),
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return $this->ok('از حساب کاربری خارج شدید.', ['redirect' => route('home')]);
        }

        return redirect()->route('home')->with('success', 'از حساب کاربری خارج شدید.');
    }

    // ------------------------------------------------------ password reset
    public function forgot()
    {
        return view('auth.forgot-password');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => ['required', 'email']], [
            'email.required' => 'ایمیل خود را وارد کنید.',
            'email.email' => 'ایمیل واردشده معتبر نیست.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::ResetLinkSent
            ? $this->ok('لینک بازیابی رمز عبور برای شما ارسال شد.')
            : $this->fail('کاربری با این ایمیل پیدا نشد.');
    }

    public function reset(string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => request('email')]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PasswordReset
            ? $this->ok('رمز عبور شما تغییر کرد.', ['redirect' => route('login')])
            : $this->fail('لینک بازیابی نامعتبر یا منقضی شده است.');
    }

    protected function redirectTarget(Request $request): string
    {
        if ($request->filled('intended')) {
            return $request->input('intended');
        }

        if (Auth::user()->isAdmin()) {
            return route('admin.dashboard');
        }

        return session()->pull('url.intended', route('account.dashboard'));
    }
}
