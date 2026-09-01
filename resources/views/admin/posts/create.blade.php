@extends('layouts.admin')

@section('title', 'نوشتن مطلب')
@section('heading', 'نوشتن مطلب تازه')
@section('subheading', 'محتوای مفید، بهترین راه جذب مشتری تازه است')

@section('breadcrumb')
    <a href="{{ route('admin.posts.index') }}" class="link-muted">وبلاگ</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="text-ink-700">مطلب جدید</span>
@endsection

@section('content')
    @include('admin.posts.partials.form', ['post' => null])
@endsection
