<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CheckoutService $checkout,
    ) {}

    public function index(Request $request)
    {
        $this->cart->pruneUnavailable();

        if ($this->cart->selectedItems()->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'برای ادامه، حداقل یک کالا را در سبد خرید انتخاب کنید.');
        }

        $addresses = $request->user()->addresses()->latest('is_default')->get();
        $methods = ShippingMethod::active()->get();

        return view('pages.checkout', [
            'items' => $this->cart->selectedItems(),
            'summary' => $this->cart->summary($methods->first()),
            'addresses' => $addresses,
            'methods' => $methods,
            'breadcrumbs' => [
                ['label' => 'سبد خرید', 'url' => route('cart.index')],
                ['label' => 'تکمیل سفارش', 'url' => route('checkout.index')],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address_id' => ['required', 'integer'],
            'shipping_method_id' => ['nullable', 'integer'],
            'payment_method' => ['required', 'in:online,cod,wallet'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [], [
            'address_id' => 'آدرس تحویل',
            'payment_method' => 'روش پرداخت',
        ]);

        $address = $request->user()->addresses()->findOrFail($data['address_id']);
        $method = $data['shipping_method_id'] ? ShippingMethod::find($data['shipping_method_id']) : null;

        try {
            $order = $this->checkout->place($address, $method, $data['payment_method'], $data['note'] ?? null);
        } catch (RuntimeException $e) {
            return $this->fail($e->getMessage());
        }

        return $this->ok('سفارش شما ثبت شد.', [
            'redirect' => route('checkout.payment', $order),
            'order_code' => $order->code,
        ]);
    }

    public function payment(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        if (! $order->is_payable) {
            return redirect()->route('checkout.result', $order);
        }

        return view('pages.checkout-payment', compact('order'));
    }

    public function pay(Request $request, Order $order)
    {
        $this->authorize('pay', $order);

        // No gateway credentials are configured out of the box, so Digino uses
        // an explicit manual confirmation step instead of faking a redirect to
        // a payment provider. Plug your merchant id into config/services.php
        // and replace this block with the real gateway call.
        if (! config('services.zarinpal.merchant_id')) {
            $this->checkout->markPaid($order, 'MANUAL-'.strtoupper(bin2hex(random_bytes(4))));

            return $this->ok('پرداخت ثبت شد.', ['redirect' => route('checkout.result', $order)]);
        }

        return $this->fail('اتصال به درگاه پرداخت در حال حاضر امکان‌پذیر نیست.');
    }

    public function result(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items');

        return view('pages.checkout-result', compact('order'));
    }
}
