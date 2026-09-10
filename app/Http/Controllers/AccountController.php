<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index(Request $r, string $section = 'dashboard')
    {
        abort_unless(in_array($section, ['dashboard', 'orders', 'favorites', 'recent', 'addresses', 'profile', 'tickets', 'settings']), 404);
        $user = $r->user();

        return view('account.index', compact('section', 'user') + ['orders' => $user->orders()->with('items')->latest()->paginate(10), 'favorites' => $user->favorites()->where('active', true)->get(), 'recent' => Product::whereIn('id', session('recent', []))->where('active', true)->get(), 'addresses' => $user->addresses, 'tickets' => $user->tickets()->latest()->get()]);
    }

    public function order(Request $r, Order $order)
    {
        abort_unless($order->user_id === $r->user()->id, 403);

        return view('account.order', ['order' => $order->load('items'), 'section' => 'orders', 'user' => $r->user()]);
    }

    public function cancel(Request $r, Order $order, OrderService $service)
    {
        abort_unless($order->user_id === $r->user()->id, 403);
        $service->transition($order, 'cancelled');

        return response()->json(['message' => 'سفارش لغو شد.', 'reload' => true]);
    }

    public function favorite(Request $r, Product $product)
    {
        abort_unless($product->active, 404);
        $r->user()->favorites()->toggle($product->id);

        return response()->json(['active' => $r->user()->favorites()->where('products.id', $product->id)->exists(), 'message' => 'فهرست علاقه‌مندی‌ها به‌روز شد.']);
    }

    public function address(Request $r, ?int $id = null)
    {
        $d = $r->validate(['name' => 'required|string|max:100', 'phone' => ['required', 'regex:/^09[0-9]{9}$/'], 'city' => 'required|string|max:100', 'postal_code' => ['required', 'regex:/^[0-9]{10}$/'], 'address' => 'required|string|min:10|max:1000']);
        if ($id) {
            $r->user()->addresses()->findOrFail($id)->update($d);
        } else {
            $r->user()->addresses()->create($d);
        }

return response()->json(['message' => 'آدرس ذخیره شد.', 'reload' => true]);
    }

    public function deleteAddress(Request $r, int $id)
    {
        $r->user()->addresses()->findOrFail($id)->delete();

        return response()->json(['message' => 'آدرس حذف شد.', 'reload' => true]);
    }

    public function profile(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:100', 'phone' => ['nullable', 'regex:/^09[0-9]{9}$/'], 'email' => ['required', 'email', 'max:190', Rule::unique('users')->ignore($r->user()->id)]]);
        $r->user()->update($d);

        return response()->json(['message' => 'اطلاعات حساب ذخیره شد.', 'reload' => true]);
    }

    public function password(Request $r)
    {
        $d = $r->validate(['current_password' => 'required|current_password', 'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()]]);
        $r->user()->update(['password' => $d['password']]);
        $r->session()->regenerate();

        return response()->json(['message' => 'رمز عبور تغییر کرد.', 'reset' => true]);
    }

    public function ticket(Request $r)
    {
        $d = $r->validate(['subject' => 'required|string|max:150', 'body' => 'required|string|min:10|max:5000']);
        $r->user()->tickets()->create($d);

        return response()->json(['message' => 'درخواست پشتیبانی ثبت شد.', 'reload' => true]);
    }
}
