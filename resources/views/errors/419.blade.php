@extends('layouts.app')

@section('title', 'نشست شما منقضی شده است | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('errors.body', [
        'code' => '419',
        'icon' => 'clock',
        'heading' => 'نشست شما منقضی شده است',
        'message' => 'به دلایل امنیتی، فرم پس از مدتی بی‌استفاده ماندن باطل می‌شود. لطفاً صفحه را تازه کنید و دوباره تلاش کنید.',
    ])
@endsection
