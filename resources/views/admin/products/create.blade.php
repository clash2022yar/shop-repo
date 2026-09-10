@extends('layouts.admin')
@section('title','افزودن محصول')
@section('page_title','افزودن محصول')
@section('content')
<form id="createForm" class="bg-white rounded-xl border p-6 space-y-5" onsubmit="return submitCreate(event)">
    <div class="grid md:grid-cols-2 gap-4">
        <div><label class="text-xs font-bold">نام محصول *</label><input name="name" required placeholder="مثلا Galaxy S24 Ultra" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm outline-none focus:border-[#ef4056]/30 focus:ring-2 focus:ring-[#ef4056]/10"></div>
        <div><label class="text-xs font-bold">دسته‌بندی *</label><select name="category_id" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"><option value="1">موبایل</option><option value="2">لپتاپ</option><option value="3">تبلت</option><option value="4">ساعت هوشمند</option><option value="5">هدفون</option></select></div>
        <div><label class="text-xs font-bold">قیمت (تومان) *</label><input name="price" type="number" required class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
        <div><label class="text-xs font-bold">قیمت با تخفیف</label><input name="discount_price" type="number" placeholder="اختیاری" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
        <div><label class="text-xs font-bold">موجودی</label><input name="stock" type="number" value="10" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
        <div><label class="text-xs font-bold">برند</label><select class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"><option>Apple</option><option>Samsung</option><option>Xiaomi</option></select></div>
    </div>
    <div><label class="text-xs font-bold">توضیحات کوتاه</label><textarea rows="2" class="mt-1 w-full border rounded-lg p-3 text-sm" placeholder="توضیح کوتاه..."></textarea></div>
    <div><label class="text-xs font-bold">توضیحات کامل</label><textarea rows="4" class="mt-1 w-full border rounded-lg p-3 text-sm" placeholder="توضیحات کامل محصول..."></textarea></div>
    
    <div>
        <label class="text-xs font-bold">تصاویر محصول</label>
        <div id="dropZone" class="mt-2 border-2 border-dashed rounded-xl p-6 text-center hover:border-[#ef4056]/30 hover:bg-[#fff1f2]/30 transition cursor-pointer" onclick="document.getElementById('fileInput').click()">
            <input type="file" id="fileInput" multiple accept="image/*" class="hidden" onchange="handleFiles(this.files)">
            <div class="w-10 h-10 mx-auto bg-gray-100 rounded-full flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
            <div class="text-sm font-medium mt-2">تصاویر را بکشید و رها کنید</div><div class="text-xs text-gray-500">یا کلیک کنید (حداکثر 6 تصویر)</div>
        </div>
        <div id="preview" class="grid grid-cols-6 gap-2 mt-3"></div>
    </div>

    <div class="flex gap-3 pt-4 border-t">
        <button type="submit" class="h-10 px-8 bg-[#ef4056] hover:bg-[#d32f44] text-white rounded-lg font-bold flex items-center gap-2"><span id="createText">ذخیره محصول</span><svg id="createSpin" class="hidden animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" opacity="0.3"/><path d="M12 2A10 10 0 0 1 22 12" stroke="white" stroke-width="3"/></svg></button>
        <a href="/admin/products" class="h-10 px-6 border rounded-lg flex items-center">انصراف</a>
        <label class="flex items-center gap-2 mr-auto text-xs"><input type="checkbox" checked class="accent-[#ef4056]"> فعال</label>
    </div>
</form>
@endsection
@push('scripts')
<script>
function handleFiles(files){
  const prev=document.getElementById('preview'); prev.innerHTML='';
  Array.from(files).slice(0,6).forEach(f=>{
    const url=URL.createObjectURL(f);
    prev.innerHTML+=`<div class="relative group"><img src="${url}" class="w-full h-20 object-cover rounded-lg border"><button onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-[#ef4056] text-white rounded-full hidden group-hover:flex items-center justify-center">×</button></div>`;
  });
}
function submitCreate(e){
  e.preventDefault();
  const btn=e.target.querySelector('button[type="submit"]'); btn.disabled=true;
  document.getElementById('createText').textContent='در حال ذخیره...'; document.getElementById('createSpin').classList.remove('hidden');
  const fd=new FormData(e.target);
  fetch('/admin/products',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest','Accept':'application/json'},body:fd})
    .then(r=>r.json()).then(j=>{
      showToast('محصول با موفقیت ساخته شد (AJAX)','success');
      setTimeout(()=> location.href='/admin/products',800);
    }).catch(()=>{
      showToast('محصول ساخته شد (دمو)','success');
      setTimeout(()=> location.href='/admin/products',800);
    });
  return false;
}
const dz=document.getElementById('dropZone');
dz.addEventListener('dragover',e=>{e.preventDefault(); dz.classList.add('border-[#ef4056]','bg-[#fff1f2]')});
dz.addEventListener('dragleave',()=> dz.classList.remove('border-[#ef4056]','bg-[#fff1f2]'));
dz.addEventListener('drop',e=>{e.preventDefault(); handleFiles(e.dataTransfer.files)});
</script>
@endpush
