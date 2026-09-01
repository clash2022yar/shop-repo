<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return view('admin.shipping.index', [
            'methods' => ShippingMethod::orderBy('sort_order')->get(),
        ]);
    }

    public function show(ShippingMethod $method)
    {
        return $this->ok('', ['method' => $method]);
    }

    public function store(Request $request)
    {
        $method = ShippingMethod::create($this->validated($request));

        return $this->ok('روش ارسال افزوده شد.', ['id' => $method->id]);
    }

    public function update(Request $request, ShippingMethod $method)
    {
        $method->update($this->validated($request));

        return $this->ok('روش ارسال به‌روزرسانی شد.');
    }

    public function destroy(ShippingMethod $method)
    {
        if ($method->id && \App\Models\Order::where('shipping_method_id', $method->id)->exists()) {
            return $this->fail('این روش ارسال در سفارش‌ها استفاده شده و قابل حذف نیست.');
        }

        $method->delete();

        return $this->ok('روش ارسال حذف شد.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:250'],
            'icon' => ['nullable', 'string', 'max:60'],
            'cost' => ['required', 'integer', 'min:0'],
            'free_from' => ['nullable', 'integer', 'min:0'],
            'estimated_days' => ['nullable', 'integer', 'min:0', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'نام روش ارسال را وارد کنید.',
            'cost.required' => 'هزینه ارسال را وارد کنید.',
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
