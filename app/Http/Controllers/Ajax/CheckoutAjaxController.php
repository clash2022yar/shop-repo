<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutAjaxController extends Controller
{
    public function __construct(protected CartService $cart) {}

    /** Recompute totals when the shipping method changes. */
    public function shipping(Request $request)
    {
        $method = ShippingMethod::active()->find($request->integer('shipping_method_id'));

        return $this->ok('', ['summary' => $this->cart->summary($method)]);
    }

    public function summary(Request $request)
    {
        $method = ShippingMethod::active()->find($request->integer('shipping_method_id'));

        return $this->ok('', ['summary' => $this->cart->summary($method)]);
    }

    /** Add a new delivery address straight from the checkout modal. */
    public function storeAddress(Request $request)
    {
        $request->merge([
            'receiver_mobile' => en_number($request->input('receiver_mobile')),
            'postal_code' => en_number($request->input('postal_code')),
        ]);

        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:60'],
            'receiver_name' => ['required', 'string', 'max:120'],
            'receiver_mobile' => ['required', 'regex:/^09\d{9}$/'],
            'province' => ['required', 'string', Rule::in(\App\Support\Iran::provinces())],
            'city' => ['required', 'string', 'max:80'],
            'line' => ['required', 'string', 'max:500'],
            'plate' => ['nullable', 'string', 'max:20'],
            'unit' => ['nullable', 'string', 'max:20'],
            'postal_code' => ['nullable', 'regex:/^\d{10}$/'],
        ], [
            'receiver_name.required' => 'نام تحویل‌گیرنده را وارد کنید.',
            'receiver_mobile.required' => 'شماره تماس تحویل‌گیرنده را وارد کنید.',
            'receiver_mobile.regex' => 'شماره موبایل باید با ۰۹ شروع شود و ۱۱ رقم باشد.',
            'province.required' => 'استان را انتخاب کنید.',
            'province.in' => 'استان انتخاب‌شده معتبر نیست.',
            'city.required' => 'شهر را وارد کنید.',
            'line.required' => 'نشانی پستی را وارد کنید.',
            'postal_code.regex' => 'کد پستی باید ۱۰ رقم باشد.',
        ]);

        $data['is_default'] = $request->user()->addresses()->doesntExist() || $request->boolean('is_default');

        $address = $request->user()->addresses()->create($data);

        return $this->ok('آدرس جدید ذخیره شد.', [
            'address' => $address->only(['id', 'label', 'receiver_name', 'receiver_mobile', 'postal_code']) + [
                'full' => $address->full,
            ],
            'html' => view('partials.address-option', compact('address'))->render(),
        ]);
    }

    public function selectAddress(Request $request)
    {
        $address = $request->user()->addresses()->findOrFail($request->integer('address_id'));

        session(['checkout.address_id' => $address->id]);

        return $this->ok('آدرس انتخاب شد.', ['address_id' => $address->id]);
    }
}
