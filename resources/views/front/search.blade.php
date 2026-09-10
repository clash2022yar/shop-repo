@extends('layouts.app')
@section('title','نتایج جستجو')
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-6">
    <div class="bg-white rounded-xl border p-6 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.6"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        <h1 class="font-bold">نتایج جستجو برای "{{ request('q') }}"</h1>
        <p class="text-sm text-gray-500 mt-2"> {{ request('q') ? 'در حال جستجو...' : 'عبارتی برای جستجو وارد کنید' }}</p>
        <div id="searchResults" class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 text-right"></div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const q = new URLSearchParams(location.search).get('q')||'';
if(q){
  const res = Array(6).fill(0).map((_,i)=>`<div class="border rounded-xl p-3 hover:shadow-lg transition"><div class="h-24 bg-gray-50 rounded-lg flex items-center justify-center"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div><div class="text-xs mt-2 font-medium">نتیجه برای ${q} - محصول ${i+1}</div><div class="text-xs font-bold mt-1">${(1000000*(i+5)).toLocaleString('fa-IR')} تومان</div></div>`);
  document.getElementById('searchResults').innerHTML=res.join('');
}
let debounce;
document.querySelector('input[name="q"]')?.addEventListener('input',e=>{
  clearTimeout(debounce);
  debounce=setTimeout(()=>{
    if(e.target.value.length>2){
      fetch(`/search?q=${encodeURIComponent(e.target.value)}`,{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.text()).then(()=>showToast('جستجو بروز شد','success'));
    }
  },400);
});
</script>
@endpush
