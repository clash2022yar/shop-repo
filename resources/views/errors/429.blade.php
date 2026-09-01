@extends('layouts.app')

@section('title', 'درخواست‌های بیش از حد | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('errors.body', [
        'code' => '429',
        'icon' => 'clock',
        'heading' => 'درخواست‌های بیش از حد',
        'message' => 'در بازه زمانی کوتاه درخواست‌های زیادی ارسال شده است. چند لحظه صبر کنید و سپس دوباره امتحان کنید.',
    ])
@endsection
