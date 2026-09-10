@extends('layouts.admin')
@section('title','ویرایش محصول')
@section('page_title','ویرایش محصول')
@section('content')
<div class="bg-white rounded-xl border p-6">
    <form class="space-y-4" onsubmit="event.preventDefault(); fetch('/admin/products/1',{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({name:this.name.value})}).then(()=> showToast('بروزرسانی شد','success'));">
        <div class="grid md:grid-cols-2 gap-4">
            <div><label class="text-xs font-bold">نام محصول</label><input name="name" value="Galaxy S24 Ultra" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
            <div><label class="text-xs font-bold">قیمت</label><input value="56900000" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
        </div>
        <div><label class="text-xs font-bold">توضیحات</label><textarea rows="4" class="mt-1 w-full border rounded-lg p-3 text-sm">توضیحات محصول...</textarea></div>
        <div class="flex gap-2">
            <button type="submit" class="h-10 px-6 bg-[#ef4056] text-white rounded-lg font-bold hover:bg-[#d32f44]">بروزرسانی (AJAX)</button>
            <a href="/admin/products" class="h-10 px-6 border rounded-lg flex items-center">بازگشت</a>
        </div>
        <div class="text-xs text-gray-500">این فرم با AJAX بدون رفرش ذخیره می‌شود (Laravel Middleware + Modal)</div>
    </form>
</div>
@endsection
