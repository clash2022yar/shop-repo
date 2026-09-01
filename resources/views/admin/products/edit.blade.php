@extends('layouts.admin')

@section('title', 'ویرایش ' . $product->name)
@section('heading', 'ویرایش کالا')
@section('subheading', $product->name)

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}" class="link-muted">محصولات</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="truncate text-ink-700">{{ $product->name }}</span>
@endsection

@section('content')
    @include('admin.products.partials.form', ['product' => $product])
@endsection
