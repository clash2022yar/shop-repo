@extends('layouts.app')

@section('title', 'دیجی‌نو موقتاً در دسترس نیست | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('errors.body', [
        'code' => '503',
        'icon' => 'tools',
        'heading' => 'دیجی‌نو موقتاً در دسترس نیست',
        'message' => 'در حال به‌روزرسانی سرویس هستیم تا تجربه خرید بهتری داشته باشید. تا دقایقی دیگر باز می‌گردیم.',
    ])
@endsection
