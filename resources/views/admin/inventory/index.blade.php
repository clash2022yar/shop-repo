@extends('layouts.admin')
@section('title','موجودی')
@section('page_title','مدیریت موجودی')
@section('content')
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center"><h3 class="font-bold text-sm">موجودی انبار</h3><button onclick="showToast('انبارگردانی (دمو)','info')" class="h-8 px-4 border rounded-lg text-xs">انبارگردانی</button></div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500"><tr><th class="p-3 text-right">محصول</th><th class="p-3 text-right">موجودی</th><th class="p-3 text-right">هشدار کمبود</th><th class="p-3 text-right">عملیات</th></tr></thead>
        <tbody class="divide-y">
            @for($i=1;$i<=8;$i++)
            <tr class="hover:bg-gray-50">
                <td class="p-3">Galaxy S24 Ultra</td>
                <td class="p-3"><input type="number" value="{{ 20-$i*2 }}" class="w-20 h-8 border rounded-lg text-center text-sm"></td>
                <td class="p-3"><span class="px-2 py-1 rounded-full text-xs {{ $i>5?'bg-red-50 text-red-600 border border-red-200':'bg-green-50 text-green-700 border border-green-200' }}">{{ $i>5?'کم':'کافی' }}</span></td>
                <td class="p-3"><button onclick="fetch('/admin/inventory/{{$i}}',{method:'PATCH',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({stock:10})}).then(()=> showToast('موجودی بروز شد','success'))" class="h-7 px-3 bg-[#fff1f2] text-[#ef4056] border border-[#fecdd3] rounded-full text-xs hover:bg-[#ef4056] hover:text-white transition">ذخیره AJAX</button></td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>
@endsection
