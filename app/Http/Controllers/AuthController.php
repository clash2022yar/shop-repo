<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt($d, $r->boolean('remember'))) {
            return response()->json(['message' => 'ایمیل یا رمز عبور نادرست است'], 422);
        }$r->session()->regenerate();

        return response()->json(['message' => 'با موفقیت وارد شدید', 'redirect' => Auth::user()->is_admin ? route('admin.dashboard') : route('dashboard')]);
    }

    public function register(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:100', 'email' => 'required|email|unique:users', 'phone' => 'nullable|string|max:20', 'password' => 'required|string|min:8|confirmed']);
        $u = User::create($d);
        Auth::login($u);
        $r->session()->regenerate();

        return response()->json(['message' => 'حساب شما ساخته شد', 'redirect' => route('dashboard')]);
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return response()->json(['message' => 'از حساب خارج شدید', 'redirect' => route('home')]);
    }
}
