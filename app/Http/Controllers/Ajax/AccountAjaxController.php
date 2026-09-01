<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountAjaxController extends Controller
{
    // ------------------------------------------------------------- profile
    public function updateProfile(Request $request)
    {
        $request->merge([
            'mobile' => en_number($request->input('mobile')),
            'national_code' => en_number($request->input('national_code')),
        ]);

        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users')->ignore($user->id)],
            'national_code' => ['nullable', 'regex:/^\d{10}$/'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'newsletter' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'نام و نام خانوادگی را وارد کنید.',
            'email.unique' => 'این ایمیل قبلاً استفاده شده است.',
            'mobile.regex' => 'شماره موبایل معتبر نیست.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'national_code.regex' => 'کد ملی باید ۱۰ رقم باشد.',
        ]);

        $data['newsletter'] = $request->boolean('newsletter');
        $user->update($data);

        return $this->ok('اطلاعات حساب کاربری به‌روزرسانی شد.', ['user' => $user->only(['name', 'email', 'mobile'])]);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'رمز عبور فعلی را وارد کنید.',
            'password.required' => 'رمز عبور جدید را وارد کنید.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
        ]);

        if (! Hash::check($data['current_password'], $request->user()->password)) {
            return $this->fail('رمز عبور فعلی نادرست است.', [
                'errors' => ['current_password' => ['رمز عبور فعلی نادرست است.']],
            ]);
        }

        $request->user()->update(['password' => $data['password']]);

        return $this->ok('رمز عبور با موفقیت تغییر کرد.');
    }

    // ----------------------------------------------------------- addresses
    public function storeAddress(Request $request)
    {
        $data = $this->validateAddress($request);
        $data['is_default'] = $request->user()->addresses()->doesntExist() || $request->boolean('is_default');

        $address = $request->user()->addresses()->create($data);

        return $this->ok('آدرس جدید افزوده شد.', [
            'html' => view('account.partials.address-card', compact('address'))->render(),
            'id' => $address->id,
        ]);
    }

    public function updateAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $address->update($this->validateAddress($request) + ['is_default' => $request->boolean('is_default')]);

        return $this->ok('آدرس به‌روزرسانی شد.', [
            'html' => view('account.partials.address-card', ['address' => $address->fresh()])->render(),
        ]);
    }

    public function destroyAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $address->delete();

        return $this->ok('آدرس حذف شد.');
    }

    public function makeDefaultAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $address->update(['is_default' => true]);

        return $this->ok('این آدرس به‌عنوان آدرس پیش‌فرض انتخاب شد.');
    }

    protected function validateAddress(Request $request): array
    {
        $request->merge([
            'receiver_mobile' => en_number($request->input('receiver_mobile')),
            'postal_code' => en_number($request->input('postal_code')),
        ]);

        return $request->validate([
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
            'receiver_mobile.required' => 'شماره تماس را وارد کنید.',
            'receiver_mobile.regex' => 'شماره موبایل معتبر نیست.',
            'province.required' => 'استان را انتخاب کنید.',
            'city.required' => 'شهر را وارد کنید.',
            'line.required' => 'نشانی را وارد کنید.',
            'postal_code.regex' => 'کد پستی باید ۱۰ رقم باشد.',
        ]);
    }

    // -------------------------------------------------------------- orders
    public function cancelOrder(Request $request, Order $order, CheckoutService $checkout)
    {
        $this->authorize('cancel', $order);

        $checkout->cancel($order, $request->input('reason') ?: 'لغو توسط مشتری.');

        return $this->ok('سفارش شما لغو شد و موجودی کالاها بازگردانده شد.', [
            'status' => $order->fresh()->status->label(),
        ]);
    }

    // ------------------------------------------------------------- tickets
    public function storeTicket(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'department' => ['required', 'in:support,orders,technical,finance'],
            'priority' => ['required', 'in:low,normal,high'],
            'order_id' => ['nullable', 'integer'],
            'body' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'subject.required' => 'موضوع تیکت را وارد کنید.',
            'body.required' => 'متن پیام را بنویسید.',
            'body.min' => 'متن پیام باید حداقل ۱۰ نویسه باشد.',
        ]);

        $ticket = $request->user()->tickets()->create([
            'subject' => $data['subject'],
            'department' => $data['department'],
            'priority' => $data['priority'],
            'order_id' => $data['order_id'] ?? null,
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return $this->ok('تیکت شما ثبت شد.', ['redirect' => route('account.tickets.show', $ticket)]);
    }

    public function replyTicket(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);
        abort_if($ticket->status === 'closed', 422, 'این تیکت بسته شده است.');

        $data = $request->validate(['body' => ['required', 'string', 'min:2', 'max:3000']], [
            'body.required' => 'متن پیام را بنویسید.',
        ]);

        $message = $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $ticket->update(['status' => 'open']);

        return $this->ok('پاسخ شما ارسال شد.', [
            'html' => view('account.partials.ticket-message', ['message' => $message->load('user')])->render(),
        ]);
    }

    public function closeTicket(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $ticket->update(['status' => 'closed']);

        return $this->ok('تیکت بسته شد.');
    }
}
