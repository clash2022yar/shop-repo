<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function show(Request $r)
    {
        return view('account.auth', ['register' => $r->is('register')]);
    }

    public function login(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt($d, $r->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'ایمیل یا رمز عبور صحیح نیست.']);
        }$r->session()->regenerate();

        return response()->json(['redirect' => session()->pull('url.intended', $r->user()->is_admin ? '/admin' : '/account'), 'message' => 'خوش آمدید.']);
    }

    public function register(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:100', 'email' => 'required|email|max:190|unique:users', 'phone' => ['nullable', 'regex:/^09[0-9]{9}$/'], 'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()], 'terms' => 'accepted']);
        $user = User::create($d);
        Auth::login($user);
        $r->session()->regenerate();

        return response()->json(['redirect' => '/account', 'message' => 'حساب کاربری شما ساخته شد.']);
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return response()->json(['redirect' => '/', 'message' => 'از حساب خارج شدید.']);
    }
}
