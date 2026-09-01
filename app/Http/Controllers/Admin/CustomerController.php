<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.customers.index', [
            'customers' => $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString(),
            'stats' => [
                'total' => User::customers()->count(),
                'active' => User::customers()->where('is_active', true)->count(),
                'new_month' => User::customers()->where('created_at', '>=', now()->subDays(30))->count(),
                'with_orders' => User::customers()->whereHas('orders')->count(),
            ],
        ]);
    }

    public function table(Request $request)
    {
        $customers = $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString();

        return $this->ok('', [
            'html' => view('admin.customers.partials.rows', compact('customers'))->render(),
            'pagination' => $customers->links()->render(),
            'total' => $customers->total(),
        ]);
    }

    public function show(User $user)
    {
        return view('admin.customers.show', [
            'customer' => $user->load(['addresses', 'reviews.product']),
            'orders' => $user->orders()->with('items')->paginate(10),
            'totals' => [
                'orders' => $user->orders()->count(),
                'paid' => (int) $user->orders()->paid()->sum('grand_total'),
                'avg' => (int) round($user->orders()->paid()->avg('grand_total') ?? 0),
                'last' => $user->orders()->latest()->first()?->created_at,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['mobile' => en_number($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users')],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users')],
            'password' => ['required', Password::defaults()],
        ], [
            'name.required' => 'نام مشتری را وارد کنید.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
        ]);

        $user = User::create($data + ['role' => UserRole::Customer->value, 'is_active' => true]);

        return $this->ok('مشتری جدید ثبت شد.', ['id' => $user->id]);
    }

    public function update(Request $request, User $user)
    {
        $request->merge(['mobile' => en_number($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users')->ignore($user->id)],
            'loyalty_points' => ['nullable', 'integer', 'min:0'],
            'password' => ['nullable', Password::defaults()],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return $this->ok('اطلاعات مشتری به‌روزرسانی شد.');
    }

    public function toggle(User $user)
    {
        abort_if($user->isSuperAdmin(), 403, 'حساب مدیر کل را نمی‌توان غیرفعال کرد.');

        $user->update(['is_active' => ! $user->is_active]);

        return $this->ok($user->is_active ? 'حساب کاربر فعال شد.' : 'حساب کاربر غیرفعال شد.', [
            'is_active' => $user->is_active,
        ]);
    }

    public function destroy(User $user)
    {
        abort_if($user->isAdmin(), 403, 'حساب‌های مدیریتی از این بخش حذف نمی‌شوند.');

        if (Order::where('user_id', $user->id)->exists()) {
            return $this->fail('این مشتری سفارش ثبت‌شده دارد؛ به‌جای حذف، حساب را غیرفعال کنید.');
        }

        $user->delete();

        return $this->ok('مشتری حذف شد.');
    }

    protected function query(Request $request)
    {
        return User::customers()
            ->withCount('orders')
            ->withSum(['orders as paid_sum' => fn ($q) => $q->where('payment_status', 'paid')], 'grand_total')
            ->search($request->input('q'))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->input('status') === 'buyers', fn ($q) => $q->has('orders'))
            ->when($request->input('sort') === 'orders', fn ($q) => $q->orderByDesc('orders_count'),
                fn ($q) => $q->latest());
    }
}
