<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Persian users type Persian digits — normalise before validating.
        $request->merge(['mobile' => en_number($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users', 'mobile')],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'نام و نام خانوادگی را وارد کنید.',
            'name.min' => 'نام باید حداقل ۳ نویسه باشد.',
            'mobile.required' => 'شماره موبایل را وارد کنید.',
            'mobile.regex' => 'شماره موبایل باید با ۰۹ شروع شود و ۱۱ رقم باشد.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'email.required' => 'ایمیل را وارد کنید.',
            'email.email' => 'ایمیل واردشده معتبر نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'password.required' => 'رمز عبور را وارد کنید.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
            'terms.accepted' => 'برای ثبت‌نام باید قوانین دیجی‌نو را بپذیرید.',
        ]);

        $guestSession = $request->session()->getId();

        $user = User::create([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => UserRole::Customer->value,
            'newsletter' => $request->boolean('newsletter'),
        ]);

        event(new Registered($user));

        Auth::login($user, true);
        $request->session()->regenerate();

        $this->cart->mergeGuestCart($user->id, $guestSession);

        return $this->ok('حساب کاربری شما ساخته شد. خوش آمدید!', [
            'redirect' => route('account.dashboard'),
        ]);
    }
}
