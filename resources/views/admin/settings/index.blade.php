@extends('layouts.admin')

@section('title', 'تنظیمات')
@section('heading', 'تنظیمات فروشگاه')
@section('subheading', 'پیکربندی عمومی، فروش، شبکه‌های اجتماعی و حالت تعمیر')

@section('breadcrumb')
    <span class="text-ink-700">تنظیمات</span>
@endsection

@section('content')
@php
    $groups = [
        'general' => ['label' => 'عمومی', 'icon' => 'settings', 'help' => 'اطلاعات پایه‌ای که در سراسر سایت و فوتر نمایش داده می‌شود.'],
        'shop' => ['label' => 'فروش و سبد خرید', 'icon' => 'shopping-bag', 'help' => 'قواعد ارسال، موجودی و رفتار سبد خرید.'],
        'social' => ['label' => 'شبکه‌های اجتماعی', 'icon' => 'share', 'help' => 'نشانی صفحه‌های رسمی دیجی‌نو؛ در فوتر لینک می‌شوند.'],
        'maintenance' => ['label' => 'حالت تعمیر', 'icon' => 'tools', 'help' => 'با فعال کردن این حالت، فروشگاه برای مشتریان بسته می‌شود.'],
    ];
@endphp

<form data-ajax-form action="{{ route('admin.settings.update') }}" data-method="PUT"
      class="grid gap-4 xl:grid-cols-[16rem_minmax(0,1fr)] xl:items-start">

    {{-- ═════════ side nav ═════════ --}}
    <aside class="rounded-card bg-white p-3 shadow-card xl:sticky xl:top-24">
        <nav class="flex gap-1 overflow-x-auto scrollbar-none xl:flex-col">
            @foreach($groups as $key => $group)
                <a href="#group-{{ $key }}"
                   class="flex shrink-0 items-center gap-2.5 rounded-field px-3 py-2.5 text-2xs text-ink-600 transition-colors hover:bg-ink-50 hover:text-brand-500">
                    <x-icon :name="$group['icon']" class="h-[18px] w-[18px]" />
                    {{ $group['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="mt-3 border-t border-ink-100 pt-3">
            <button type="button" class="btn-outline btn-sm w-full"
                    data-action="{{ route('admin.settings.cache.clear') }}" data-method="POST">
                <x-icon name="refresh" class="h-4 w-4" />
                پاک‌سازی حافظه پنهان
            </button>
        </div>
    </aside>

    {{-- ═════════ fields ═════════ --}}
    <div class="space-y-4">
        @foreach($schema as $key => $fields)
            <section id="group-{{ $key }}" class="scroll-mt-24 rounded-card bg-white p-5 shadow-card" data-reveal>
                <div class="mb-5 border-b border-ink-100 pb-4">
                    <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                        <x-icon :name="$groups[$key]['icon'] ?? 'settings'" class="h-5 w-5 text-brand-500" />
                        {{ $groups[$key]['label'] ?? $key }}
                    </h2>
                    <p class="mt-1.5 text-[11px] leading-6 text-ink-500">{{ $groups[$key]['help'] ?? '' }}</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($fields as $name => $meta)
                        @php $value = $values[$name] ?? null; @endphp

                        @if($meta['type'] === 'bool')
                            <div class="sm:col-span-2">
                                <x-switch :name="$name" :checked="(bool) $value" :label="$meta['label']" />
                            </div>
                        @elseif($meta['type'] === 'text')
                            <div class="sm:col-span-2">
                                <label class="label" for="set-{{ $name }}">{{ $meta['label'] }}</label>
                                <textarea id="set-{{ $name }}" name="{{ $name }}" rows="3" class="field">{{ $value }}</textarea>
                                <p class="error-text" data-error-for="{{ $name }}"></p>
                            </div>
                        @elseif($meta['type'] === 'int')
                            <div>
                                <label class="label" for="set-{{ $name }}">{{ $meta['label'] }}</label>
                                <input id="set-{{ $name }}" name="{{ $name }}" class="field ltr text-end"
                                       data-numeric inputmode="numeric" value="{{ $value }}">
                                <p class="error-text" data-error-for="{{ $name }}"></p>
                            </div>
                        @else
                            <div>
                                <label class="label" for="set-{{ $name }}">{{ $meta['label'] }}</label>
                                <input id="set-{{ $name }}" name="{{ $name }}"
                                       @class(['field', 'ltr' => in_array($name, ['support_email', 'instagram', 'telegram', 'linkedin', 'twitter', 'support_phone'], true)])
                                       value="{{ $value }}">
                                <p class="error-text" data-error-for="{{ $name }}"></p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="sticky bottom-4 flex items-center justify-between gap-3 rounded-card bg-ink-900 px-5 py-3.5 text-white shadow-pop">
            <p class="text-2xs text-white/70">تغییرات پس از ذخیره بلافاصله در فروشگاه اعمال می‌شود.</p>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره تنظیمات</span>
            </button>
        </div>
    </div>
</form>
@endsection
