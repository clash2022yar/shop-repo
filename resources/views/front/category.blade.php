@extends('layouts.app')
@section('title','دسته‌بندی')
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4">
    <h1 class="font-bold text-lg">دسته‌بندی: {{ $category->name ?? 'موبایل' }}</h1>
    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3" id="catGrid"></div>
</div>
@endsection
@push('scripts')
<script>
const catProducts = Array(8).fill(0).map((_,i)=>({name:'محصول دسته '+ (i+1),price:10000000+(i*2000000)}));
document.getElementById('catGrid').innerHTML = catProducts.map(p=>`
<div class="bg-white border rounded-xl p-3 hover:shadow-lg transition">
  <div class="h-32 bg-gray-50 rounded-lg flex items-center justify-center"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
  <div class="text-sm font-medium mt-2">${p.name}</div>
  <div class="font-bold text-sm mt-1">${p.price.toLocaleString('fa-IR')} تومان</div>
</div>`).join('');
</script>
@endpush
