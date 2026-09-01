@extends('layouts.admin')

@section('title', 'افزودن کالا')
@section('heading', 'افزودن کالای جدید')
@section('subheading', 'اطلاعات کالا را کامل کنید تا در فروشگاه منتشر شود')

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}" class="link-muted">محصولات</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="text-ink-700">افزودن کالا</span>
@endsection

@section('content')
    @include('admin.products.partials.form', ['product' => null])
@endsection
