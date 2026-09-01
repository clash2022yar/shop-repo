<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::with('category')
                ->when($request->filled('q'), fn ($q) => $q->where('code', 'like', '%'.strtoupper($request->input('q')).'%'))
                ->when($request->input('status') === 'active', fn ($q) => $q->usable())
                ->when($request->input('status') === 'expired', fn ($q) => $q->where('expires_at', '<', now()))
                ->latest()->paginate(config('digino.admin.per_page'))->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'stats' => [
                'total' => Coupon::count(),
                'active' => Coupon::usable()->count(),
                'used' => (int) Coupon::sum('used_count'),
            ],
        ]);
    }

    public function show(Coupon $coupon)
    {
        return $this->ok('', ['coupon' => $coupon]);
    }

    public function store(Request $request)
    {
        $coupon = Coupon::create($this->validated($request));

        return $this->ok('کد تخفیف ساخته شد.', ['id' => $coupon->id]);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($this->validated($request, $coupon));

        return $this->ok('کد تخفیف به‌روزرسانی شد.');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->used_count > 0) {
            return $this->fail('این کد قبلاً استفاده شده است؛ به‌جای حذف آن را غیرفعال کنید.');
        }

        $coupon->delete();

        return $this->ok('کد تخفیف حذف شد.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        return $this->ok($coupon->is_active ? 'کد تخفیف فعال شد.' : 'کد تخفیف غیرفعال شد.', [
            'is_active' => $coupon->is_active,
        ]);
    }

    protected function validated(Request $request, ?Coupon $coupon = null): array
    {
        $request->merge(['code' => mb_strtoupper(trim((string) $request->input('code')))]);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', 'regex:/^[A-Z0-9\-_]+$/', Rule::unique('coupons')->ignore($coupon?->id)],
            'title' => ['nullable', 'string', 'max:150'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1'],
            'max_discount' => ['nullable', 'integer', 'min:0'],
            'min_order_total' => ['nullable', 'integer', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
        ], [
            'code.required' => 'کد تخفیف را وارد کنید.',
            'code.regex' => 'کد تخفیف فقط می‌تواند شامل حروف انگلیسی بزرگ، عدد، خط تیره و زیرخط باشد.',
            'code.unique' => 'این کد قبلاً تعریف شده است.',
            'value.required' => 'مقدار تخفیف را وارد کنید.',
            'expires_at.after' => 'تاریخ انقضا باید بعد از تاریخ شروع باشد.',
        ]);

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            $data['value'] = 100;
        }

        return $data + ['is_active' => $request->boolean('is_active')];
    }
}
