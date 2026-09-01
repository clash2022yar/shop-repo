@extends('layouts.app')

@section('title', 'اجازه دسترسی به این بخش را ندارید | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('errors.body', [
        'code' => '403',
        'icon' => 'lock',
        'heading' => 'اجازه دسترسی به این بخش را ندارید',
        'message' => 'این صفحه فقط برای کاربران دارای دسترسی مجاز در دسترس است. اگر فکر می‌کنید اشتباهی رخ داده، با پشتیبانی دیجی‌نو تماس بگیرید.',
    ])
@endsection
