@extends('layouts.app')

@section('title', 'صفحه‌ای که دنبالش بودید پیدا نشد | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('errors.body', [
        'code' => '404',
        'icon' => 'search',
        'heading' => 'صفحه‌ای که دنبالش بودید پیدا نشد',
        'message' => 'ممکن است نشانی را اشتباه وارد کرده باشید یا این صفحه حذف شده باشد. از میان‌برهای زیر ادامه دهید یا کالای موردنظرتان را جست‌وجو کنید.',
    ])
@endsection
