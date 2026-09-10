@extends('layouts.admin')
@section('title','جزئیات سفارش')
@section('page_title','جزئیات سفارش #1024')
@section('content')
<div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 bg-white rounded-xl border p-5">
        <h3 class="font-bold text-sm mb-4">محصولات سفارش</h3>
        <div class="space-y-3">
            <div class="flex gap-3 border rounded-xl p-3"><div class="w-12 h-12 bg-gray-50 rounded-lg border"></div><div class="flex-1"><div class="text-sm font-medium">Galaxy S24 Ultra</div><div class="text-xs text-gray-500">1 × 56,900,000</div></div><div class="font-bold text-sm">56,900,000</div></div>
            <div class="flex gap-3 border rounded-xl p-3"><div class="w-12 h-12 bg-gray-50 rounded-lg border"></div><div class="flex-1"><div class="text-sm font-medium">WH-1000XM5</div><div class="text-xs text-gray-500">1 × 15,900,000</div></div><div class="font-bold text-sm">15,900,000</div></div>
        </div>
        <div class="mt-6 border-t pt-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">جمع</span><span>72,800,000 تومان</span></div>
            <div class="flex justify-between"><span class="text-gray-500">تخفیف</span><span class="text-[#ef4056]">0</span></div>
            <div class="flex justify-between font-black"><span>قابل پرداخت</span><span>72,800,000 تومان</span></div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-sm mb-3">وضعیت سفارش</h3>
            <select id="orderStatus" class="w-full h-10 border rounded-lg px-3 text-sm" onchange="updateStatus()">
                <option>pending - در انتظار</option><option selected>processing - در حال پردازش</option><option>shipped - ارسال شده</option><option>delivered - تحویل شده</option><option>cancelled - لغو شده</option>
            </select>
            <button onclick="updateStatus()" class="mt-3 w-full h-9 bg-[#ef4056] text-white rounded-lg text-sm font-bold hover:bg-[#d32f44]">بروزرسانی (AJAX)</button>
        </div>
        <div class="bg-white rounded-xl border p-5 text-sm">
            <h3 class="font-bold mb-3">اطلاعات مشتری</h3>
            <div>علی محمدی</div><div class="text-gray-500">09120000000 | ali@example.com</div><div class="text-gray-500 mt-2">تهران، انقلاب، پلاک 123</div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function updateStatus(){
  const status=document.getElementById('orderStatus').value;
  fetch('/admin/orders/1/status',{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({status})})
    .then(()=> showToast('وضعیت بروز شد','success')).catch(()=> showToast('بروزرسانی دمو','success'));
}
</script>
@endpush
