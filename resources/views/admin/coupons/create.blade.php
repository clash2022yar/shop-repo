@extends('layouts.admin')
@section('title','افزودن coupons')
@section('page_title','افزودن coupons')
@section('content')
<div class="bg-white rounded-xl border p-6">
    <form class="space-y-4" onsubmit="event.preventDefault(); fetch('/admin/coupons',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({name:this.name.value})}).then(()=>{showToast('ایجاد شد','success'); setTimeout(()=> location.href='/admin/coupons',600)});">
        <div><label class="text-xs font-bold">نام</label><input name="name" required class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
        <div><label class="text-xs font-bold">توضیحات</label><textarea rows="3" class="mt-1 w-full border rounded-lg p-3 text-sm"></textarea></div>
        <div class="flex gap-2"><button type="submit" class="h-10 px-6 bg-[#ef4056] text-white rounded-lg font-bold">ذخیره با AJAX</button><a href="/admin/coupons" class="h-10 px-6 border rounded-lg flex items-center">بازگشت</a></div>
    </form>
</div>
@endsection
