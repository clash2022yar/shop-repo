<!doctype html><html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><meta name="description" content="دیجینو؛ فروشگاه هوشمند کالای دیجیتال"><title>@yield('title','دیجینو | خرید هوشمند')</title><link rel="icon" href="/favicon.svg" type="image/svg+xml"><link rel="stylesheet" href="/css/app.css">@stack('head')</head><body class="@yield('body-class')">
@include('partials.icons')
@include('partials.header')
<main>@yield('content')</main>
@if(!isset($noFooter))@include('partials.footer')@endif
<div class="toast-stack" id="toasts"></div><div class="modal" id="confirm-modal"><div class="modal-card"><button data-close class="modal-x"><x-icon name="close"/></button><h3>آیا مطمئن هستید؟</h3><p id="confirm-text">این عملیات قابل بازگشت نیست.</p><div class="modal-actions"><button class="btn btn-muted" data-close>انصراف</button><button class="btn" id="confirm-action">تأیید</button></div></div></div>
<script src="/js/app.js" defer></script>@stack('scripts')</body></html>