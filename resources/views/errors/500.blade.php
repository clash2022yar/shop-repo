@extends('layouts.app')

@section('title', 'خطایی در سرور رخ داد | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('errors.body', [
        'code' => '500',
        'icon' => 'alert',
        'heading' => 'خطایی در سرور رخ داد',
        'message' => 'مشکل از سمت ما بود، نه شما. تیم فنی دیجی‌نو به‌صورت خودکار از این خطا مطلع شد و در حال بررسی است.',
    ])
@endsection
