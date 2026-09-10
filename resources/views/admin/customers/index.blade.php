@extends('layouts.admin')
@section('title','مشتریان')
@section('page_title','مشتریان')
@section('content')
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="p-4 flex gap-2 border-b"><input placeholder="جستجوی مشتری..." class="h-9 flex-1 border rounded-lg px-3 text-sm"><button class="h-9 px-4 border rounded-lg text-sm">جستجو</button><button class="h-9 px-4 bg-[#ef4056] text-white rounded-lg text-sm font-bold">خروجی</button></div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500"><tr><th class="p-3 text-right">کاربر</th><th class="p-3 text-right">ایمیل</th><th class="p-3 text-right">سفارش‌ها</th><th class="p-3 text-right">وضعیت</th><th class="p-3"></th></tr></thead>
        <tbody class="divide-y">
            @for($i=1;$i<=8;$i++)
            <tr class="hover:bg-gray-50">
                <td class="p-3 flex items-center gap-2"><div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold">{{ $i }}</div> کاربر {{$i}}</td>
                <td class="p-3 text-gray-500">user{{$i}}@example.com</td>
                <td class="p-3">{{ rand(1,12) }} سفارش</td>
                <td class="p-3"><span class="bg-green-50 text-green-700 border border-green-200 px-2 py-1 rounded-full text-xs">فعال</span></td>
                <td class="p-3"><a href="/admin/customers/{{$i}}" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></a></td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>
@endsection
