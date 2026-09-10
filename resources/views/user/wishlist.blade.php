@extends('layouts.app')
@section('title','علاقه‌مندی‌ها')
@php $hideFooter=true; @endphp
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4 flex gap-4">
  <div class="hidden lg:block w-[280px] shrink-0">@include('includes.sidebar-user')</div>
  <div class="flex-1 bg-white rounded-xl border p-6">
    <h1 class="font-bold">علاقه‌مندی‌ها (12 کالا)</h1>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-4" id="wishGrid"></div>
  </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('wishGrid').innerHTML = Array(8).fill(0).map((_,i)=>`
<div class="border rounded-xl p-3 hover:shadow-lg transition group relative">
  <button onclick="this.closest('.border').remove(); showToast('حذف شد','info')" class="absolute top-2 left-2 w-7 h-7 bg-white shadow rounded-full flex items-center justify-center text-gray-400 hover:text-[#ef4056]"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
  <div class="h-28 bg-gray-50 rounded-lg flex items-center justify-center"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
  <div class="text-xs mt-2 font-medium">محصول مورد علاقه ${i+1}</div>
  <div class="font-bold text-sm mt-1">${(10000000+i*1500000).toLocaleString('fa-IR')} تومان</div>
  <button onclick="addToCart('محصول ${i+1}',10000000)" class="mt-2 w-full h-8 bg-[#ef4056] text-white rounded-lg text-xs font-bold hover:bg-[#d32f44]">افزودن به سبد</button>
</div>`).join('');
</script>
@endpush
