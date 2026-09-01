<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        return view('admin.staff.index', [
            'staff' => User::staff()->latest()->get(),
            'roles' => UserRole::options(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['mobile' => en_number($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users')],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users')],
            'role' => ['required', 'in:manager,admin'],
            'password' => ['required', Password::defaults()],
        ], [
            'name.required' => 'نام همکار را وارد کنید.',
            'role.required' => 'نقش کاربر را انتخاب کنید.',
        ]);

        $user = User::create($data + ['is_active' => true]);

        return $this->ok('همکار جدید افزوده شد.', ['id' => $user->id]);
    }

    public function update(Request $request, User $user)
    {
        $request->merge(['mobile' => en_number($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:customer,manager,admin'],
            'password' => ['nullable', Password::defaults()],
        ]);

        // Never let the last super-admin demote themselves out of the panel.
        if ($user->isSuperAdmin() && $data['role'] !== UserRole::Admin->value
            && User::where('role', UserRole::Admin->value)->count() <= 1) {
            return $this->fail('حداقل یک «مدیر کل» باید در سیستم باقی بماند.');
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data + ['is_active' => $request->boolean('is_active', true)]);

        return $this->ok('اطلاعات همکار به‌روزرسانی شد.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return $this->fail('نمی‌توانید حساب خودتان را حذف کنید.');
        }

        if ($user->isSuperAdmin() && User::where('role', UserRole::Admin->value)->count() <= 1) {
            return $this->fail('حداقل یک «مدیر کل» باید در سیستم باقی بماند.');
        }

        $user->delete();

        return $this->ok('دسترسی همکار حذف شد.');
    }
}
