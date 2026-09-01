@extends('layouts.admin')

@section('title', 'همکاران')
@section('heading', 'همکاران و سطوح دسترسی')
@section('subheading', 'مدیریت حساب‌هایی که به پنل مدیریت دسترسی دارند')

@section('breadcrumb')
    <span class="text-ink-700">همکاران</span>
@endsection

@section('content')
<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <p class="flex items-center gap-2 text-2xs text-ink-500">
            <x-icon name="shield" class="h-5 w-5 text-info-600" />
            {{ fa_number($staff->count()) }} همکار به پنل مدیریت دسترسی دارد.
        </p>

        <button type="button" class="btn-primary btn-sm" data-modal-open="staff-form" data-crud-new="staff-form">
            <x-icon name="user-plus" class="h-4 w-4" />
            افزودن همکار
        </button>
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>همکار</th>
                        <th>موبایل</th>
                        <th>نقش</th>
                        <th>وضعیت</th>
                        <th>تاریخ عضویت</th>
                        <th class="w-28">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $member)
                        <tr data-staff-row class="animate-fade-in">
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-50 text-[11px] font-bold text-brand-500">
                                        {{ $member->initials }}
                                    </span>
                                    <div class="min-w-0">
                                        <span class="block truncate text-2xs font-medium text-ink-900">{{ $member->name }}</span>
                                        <span class="ltr block truncate text-[10px] text-ink-400">{{ $member->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="ltr text-2xs text-ink-600">{{ fa_number($member->mobile) }}</td>
                            <td>
                                <span class="{{ $member->isSuperAdmin() ? 'badge-red' : 'badge-blue' }}">{{ $member->role->label() }}</span>
                            </td>
                            <td>
                                @if($member->is_active)
                                    <span class="badge-green">فعال</span>
                                @else
                                    <span class="badge-gray">غیرفعال</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-2xs text-ink-500">{{ jalali($member->created_at) }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="btn-icon h-8 w-8" aria-label="ویرایش همکار"
                                            data-staff-edit='@json([
                                                'id' => $member->id,
                                                'name' => $member->name,
                                                'email' => $member->email,
                                                'mobile' => $member->mobile,
                                                'role' => $member->role->value,
                                                'is_active' => $member->is_active,
                                            ])'>
                                        <x-icon name="edit" class="h-4 w-4" />
                                    </button>
                                    <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف دسترسی"
                                            data-action="{{ route('admin.staff.destroy', $member) }}" data-method="DELETE"
                                            data-remove-row="[data-staff-row]"
                                            data-confirm="دسترسی «{{ $member->name }}» حذف شود؟" data-confirm-title="حذف همکار" data-confirm-accept="حذف کن">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 rounded-card bg-white p-5 shadow-card">
        <h2 class="mb-3 flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon name="info" class="h-5 w-5 text-info-600" />
            سطوح دسترسی
        </h2>
        <ul class="space-y-2.5 text-2xs leading-6 text-ink-600">
            <li class="flex gap-2"><x-icon name="check" class="mt-1 h-4 w-4 shrink-0 text-success-600" /><span><span class="font-bold text-ink-800">مدیر کل:</span> دسترسی کامل، از جمله مدیریت همکاران و تنظیمات فروشگاه.</span></li>
            <li class="flex gap-2"><x-icon name="check" class="mt-1 h-4 w-4 shrink-0 text-success-600" /><span><span class="font-bold text-ink-800">مدیر بخش:</span> مدیریت کالاها، سفارش‌ها، دیدگاه‌ها و پشتیبانی — بدون دسترسی به همکاران.</span></li>
            <li class="flex gap-2"><x-icon name="check" class="mt-1 h-4 w-4 shrink-0 text-success-600" /><span><span class="font-bold text-ink-800">مشتری:</span> فقط پنل کاربری فروشگاه.</span></li>
        </ul>
    </div>
</div>

<x-modal id="staff-form" title="افزودن همکار">
    <form data-ajax-form id="staff-form-el" action="{{ route('admin.staff.store') }}" data-method="POST" data-reload>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="st-name">نام و نام خانوادگی <span class="text-brand-500">*</span></label>
                <input id="st-name" name="name" class="field">
                <p class="error-text" data-error-for="name"></p>
            </div>

            <div>
                <label class="label" for="st-email">ایمیل <span class="text-brand-500">*</span></label>
                <input id="st-email" name="email" type="email" class="field ltr">
                <p class="error-text" data-error-for="email"></p>
            </div>

            <div>
                <label class="label" for="st-mobile">موبایل <span class="text-brand-500">*</span></label>
                <input id="st-mobile" name="mobile" class="field ltr" data-numeric inputmode="numeric" placeholder="09123456789">
                <p class="error-text" data-error-for="mobile"></p>
            </div>

            <div>
                <label class="label" for="st-role">نقش <span class="text-brand-500">*</span></label>
                <select id="st-role" name="role" class="field">
                    @foreach($roles as $value => $label)
                        @continue($value === 'customer')
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <p class="error-text" data-error-for="role"></p>
            </div>

            <div>
                <label class="label" for="st-password">گذرواژه</label>
                <input id="st-password" name="password" type="password" class="field ltr" autocomplete="new-password">
                <p class="help">در ویرایش، خالی بگذارید تا تغییری نکند.</p>
                <p class="error-text" data-error-for="password"></p>
            </div>

            <div class="sm:col-span-2">
                <x-switch name="is_active" :checked="true" label="حساب فعال باشد" />
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره همکار</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        var form = document.getElementById('staff-form-el');
        var title = document.querySelector('[data-modal="staff-form"] [data-modal-title]');

        document.querySelectorAll('[data-crud-new="staff-form"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                form.reset();
                form.setAttribute('action', '{{ route('admin.staff.store') }}');
                form.dataset.method = 'POST';
                title.textContent = 'افزودن همکار';
            });
        });

        document.querySelectorAll('[data-staff-edit]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var member = JSON.parse(btn.dataset.staffEdit);

                form.reset();
                form.setAttribute('action', '{{ url('admin/staff') }}/' + member.id);
                form.dataset.method = 'PUT';
                title.textContent = 'ویرایش ' + member.name;

                form.querySelector('[name=name]').value = member.name;
                form.querySelector('[name=email]').value = member.email;
                form.querySelector('[name=mobile]').value = member.mobile;
                form.querySelector('[name=role]').value = member.role;
                form.querySelector('[name=is_active][type=checkbox]').checked = !!member.is_active;

                dg.modal.open('staff-form');
            });
        });
    });
</script>
@endpush
