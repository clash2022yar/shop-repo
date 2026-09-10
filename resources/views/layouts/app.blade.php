<!DOCTYPE html><html lang="fa" dir="rtl"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}"><meta name="description" content="دیجینو؛ انتخاب هوشمندانه کالای دیجیتال. بررسی، مقایسه و خرید موبایل، لپ‌تاپ و لوازم دیجیتال."><meta name="theme-color" content="#ef233c"><title>@yield('title','دیجینو | خرید هوشمند کالای دیجیتال')</title><link rel="icon" type="image/svg+xml" href="/assets/logo.svg"><link rel="preload" href="/assets/fonts/Vazirmatn.woff2" as="font" type="font/woff2" crossorigin><link rel="stylesheet" href="/assets/tailwind.css"><link rel="stylesheet" href="/assets/style.css"><script src="/assets/app.js" defer></script></head><body class="{{ request()->is('admin*') ? 'admin-body' : '' }} {{ request()->is('login','register') ? 'auth-body' : '' }}"><a class="skip-link" href="#main">رفتن به محتوای اصلی</a>
@include('partials.header')
<main id="main" class="site-main">@yield('content')</main>
@if(!request()->is('login','register','account*','admin*')) @include('partials.footer') @endif
<div id="toast" role="status" aria-live="polite"></div>
<dialog id="confirm-modal" class="modal"><div class="modal-head"><h3>آیا مطمئن هستید؟</h3><button class="icon-button" data-close-modal aria-label="بستن">@include('partials.icon',['name'=>'close'])</button></div><p id="confirm-text">این عملیات انجام شود؟</p><div class="modal-actions"><button class="btn btn-primary" id="confirm-yes">بله، انجام شود</button><button class="btn btn-outline" data-close-modal>انصراف</button></div></dialog>
@stack('modals')
<button class="back-top icon-button" aria-label="بازگشت به بالا" title="بازگشت به بالا">@include('partials.icon',['name'=>'right'])</button>
</body></html>
