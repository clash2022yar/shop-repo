@extends('layouts.account')

@section('title', 'آدرس‌های من')
@section('heading', 'آدرس‌های من')
@section('subheading', 'آدرس‌های تحویل سفارش خود را مدیریت کنید.')

@section('actions')
    <button type="button" data-modal-open="add-address" class="btn-primary btn-sm">
        <x-icon name="plus" class="h-4 w-4" />
        افزودن آدرس جدید
    </button>
@endsection

@section('content')
@if($addresses->isEmpty())
    <div class="rounded-card bg-white shadow-card">
        <x-empty-state icon="map-pin" title="هنوز آدرسی ثبت نکرده‌اید"
                       message="برای تحویل سریع‌تر سفارش‌ها، آدرس خود را اضافه کنید.">
            <button type="button" data-modal-open="add-address" class="btn-primary mt-4">
                <x-icon name="plus" class="h-4 w-4" />
                افزودن آدرس
            </button>
        </x-empty-state>
    </div>
@else
    <div class="grid gap-3 stagger md:grid-cols-2">
        @foreach($addresses as $address)
            @include('account.partials.address-card', ['address' => $address])
        @endforeach
    </div>
@endif

<x-modal id="add-address" title="افزودن آدرس جدید" size="lg">
    <form action="{{ route('ajax.account.addresses.store') }}" method="POST" data-ajax-form data-close-modal data-reload>
        @csrf
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="ad-label">عنوان آدرس</label>
                <input id="ad-label" type="text" name="label" class="field" placeholder="خانه، محل کار، ...">
                <p data-error-for="label" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="ad-receiver">نام گیرنده <span class="text-brand-500">*</span></label>
                <input id="ad-receiver" type="text" name="receiver_name" class="field" required value="{{ auth()->user()->name }}">
                <p data-error-for="receiver_name" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="ad-mobile">موبایل گیرنده <span class="text-brand-500">*</span></label>
                <input id="ad-mobile" type="tel" name="receiver_mobile" class="field ltr" required value="{{ auth()->user()->mobile }}">
                <p data-error-for="receiver_mobile" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="ad-postal">کد پستی</label>
                <input id="ad-postal" type="text" name="postal_code" class="field ltr" placeholder="۱۰ رقم">
                <p data-error-for="postal_code" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="ad-province">استان <span class="text-brand-500">*</span></label>
                <select id="ad-province" name="province" class="field" required onchange="dgFillCities(this.value)">
                    <option value="">انتخاب کنید</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}">{{ $province }}</option>
                    @endforeach
                </select>
                <p data-error-for="province" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="ad-city">شهر <span class="text-brand-500">*</span></label>
                <select id="ad-city" name="city" class="field" required>
                    <option value="">ابتدا استان را انتخاب کنید</option>
                </select>
                <p data-error-for="city" class="hidden"></p>
            </div>
            <div class="sm:col-span-2">
                <label class="label" for="ad-line">نشانی دقیق <span class="text-brand-500">*</span></label>
                <textarea id="ad-line" name="line" rows="2" class="field" required></textarea>
                <p data-error-for="line" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="ad-plate">پلاک</label>
                <input id="ad-plate" type="text" name="plate" class="field">
            </div>
            <div>
                <label class="label" for="ad-unit">واحد</label>
                <input id="ad-unit" type="text" name="unit" class="field">
            </div>
        </div>

        <label class="mt-4 flex cursor-pointer items-center gap-2.5">
            <input type="hidden" name="is_default" value="0">
            <input type="checkbox" name="is_default" value="1" class="checkbox" {{ $addresses->isEmpty() ? 'checked' : '' }}>
            <span class="text-2xs text-ink-700">این آدرس، آدرس پیش‌فرض من باشد</span>
        </label>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-text>ذخیره آدرس</span>
                <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    var DG_CITIES = @json(\App\Support\Iran::map(), JSON_UNESCAPED_UNICODE);
    function dgFillCities(province) {
        var select = document.getElementById('ad-city');
        if (!select) return;
        var cities = DG_CITIES[province] || [];
        select.innerHTML = cities.length
            ? cities.map(function (c) { return '<option value="' + c + '">' + c + '</option>'; }).join('')
            : '<option value="">ابتدا استان را انتخاب کنید</option>';
    }
</script>
@endpush
