@extends('layouts.admin')

@section('title', 'ویرایش ' . $post->title)
@section('heading', 'ویرایش مطلب')
@section('subheading', $post->title)

@section('breadcrumb')
    <a href="{{ route('admin.posts.index') }}" class="link-muted">وبلاگ</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="truncate text-ink-700">{{ $post->title }}</span>
@endsection

@section('content')
    @include('admin.posts.partials.form', ['post' => $post])
@endsection
