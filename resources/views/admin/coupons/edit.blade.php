@extends('layouts.admin')
@section('title','ویرایش coupons')
@section('page_title','ویرایش coupons')
@section('content')
<div class="bg-white rounded-xl border p-6">
    <form class="space-y-4" onsubmit="event.preventDefault(); fetch('/admin/coupons/1',{method:'PATCH',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({name:this.name.value})}).then(()=> showToast('بروزرسانی شد','success'));">
        <div><label class="text-xs font-bold">نام</label><input name="name" value="نمونه" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
        <div class="flex gap-2"><button type="submit" class="h-10 px-6 bg-[#ef4056] text-white rounded-lg font-bold">بروزرسانی AJAX</button><a href="/admin/coupons" class="h-10 px-6 border rounded-lg flex items-center">بازگشت</a></div>
    </form>
</div>
@endsection
