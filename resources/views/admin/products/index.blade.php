@extends('layouts.admin')
@section('title','محصولات')
@section('page_title','محصولات')
@section('content')
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="p-4 flex flex-wrap gap-3 items-center justify-between border-b">
        <div class="flex gap-2">
            <div class="relative"><input id="prodSearch" placeholder="جستجوی محصول..." class="h-9 w-64 border rounded-lg pr-9 pl-3 text-sm outline-none focus:border-gray-300"><svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
            <select class="h-9 border rounded-lg px-3 text-sm"><option>همه دسته‌ها</option><option>موبایل</option><option>لپتاپ</option></select>
        </div>
        <div class="flex gap-2">
            <button onclick="showToast('خروجی اکسل (دمو)','info')" class="h-9 px-4 border rounded-lg text-sm hover:bg-gray-50">خروجی</button>
            <a href="/admin/products/create" class="h-9 px-4 bg-[#ef4056] hover:bg-[#d32f44] text-white rounded-lg text-sm font-bold flex items-center gap-2">+ افزودن محصول</a>
        </div>
    </div>
    <div class="overflow-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs"><tr><th class="p-3 text-right"><input type="checkbox"></th><th class="p-3 text-right">محصول</th><th class="p-3 text-right">دسته</th><th class="p-3 text-right">قیمت</th><th class="p-3 text-right">موجودی</th><th class="p-3 text-right">وضعیت</th><th class="p-3"></th></tr></thead>
            <tbody class="divide-y" id="prodTable">
                <!-- rows via JS -->
            </tbody>
        </table>
    </div>
    <div class="p-4 flex justify-between items-center border-t text-xs">
        <span class="text-gray-500">نمایش 1 تا 10 از 60 محصول</span>
        <div class="flex gap-1">
            <button class="w-8 h-8 border rounded-lg flex items-center justify-center">›</button>
            <button class="w-8 h-8 bg-[#ef4056] text-white rounded-lg">1</button>
            <button class="w-8 h-8 border rounded-lg">2</button>
            <button class="w-8 h-8 border rounded-lg">3</button>
        </div>
    </div>
</div>

<!-- Modal Edit Quick -->
<div id="editModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6">
        <h3 class="font-bold">ویرایش سریع</h3>
        <div class="mt-4 space-y-3">
            <input id="modalName" class="w-full h-10 border rounded-lg px-3 text-sm">
            <input id="modalPrice" type="number" class="w-full h-10 border rounded-lg px-3 text-sm">
            <div class="flex gap-2 pt-2">
                <button onclick="saveModal()" class="flex-1 h-10 bg-[#ef4056] text-white rounded-lg font-bold">ذخیره (AJAX)</button>
                <button onclick="closeModal()" class="flex-1 h-10 border rounded-lg">انصراف</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const products = [
 {id:1,name:'Galaxy S24 Ultra',cat:'موبایل',price:56900000,stock:23,status:'فعال'},
 {id:2,name:'iPhone 15 Pro Max',cat:'موبایل',price:72900000,stock:12,status:'فعال'},
 {id:3,name:'MacBook Air M2',cat:'لپتاپ',price:46500000,stock:5,status:'فعال'},
 {id:4,name:'WH-1000XM5',cat:'هدفون',price:15900000,stock:42,status:'فعال'},
 {id:5,name:'PlayStation 5',cat:'کنسول',price:29900000,stock:0,status:'ناموجود'},
 {id:6,name:'AirPods Pro 2',cat:'هدفون',price:11490000,stock:67,status:'فعال'},
 {id:7,name:'Watch Series 9',cat:'ساعت',price:23500000,stock:31,status:'فعال'},
 {id:8,name:'VivoBook 15',cat:'لپتاپ',price:15500000,stock:9,status:'فعال'},
];
let editId=null;
function render(){
  document.getElementById('prodTable').innerHTML = products.map(p=>`
    <tr class="hover:bg-gray-50 group">
      <td class="p-3"><input type="checkbox"></td>
      <td class="p-3"><div class="flex items-center gap-3"><div class="w-10 h-10 bg-gray-50 rounded-lg border flex items-center justify-center"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div><div><div class="font-medium">${p.name}</div><div class="text-xs text-gray-400">#SKU-${String(p.id).padStart(5,'0')}</div></div></div></td>
      <td class="p-3 text-xs">${p.cat}</td>
      <td class="p-3 font-bold text-xs">${p.price.toLocaleString('fa-IR')} تومان</td>
      <td class="p-3"><span class="px-2 py-1 rounded-full text-xs border ${p.stock==0?'bg-red-50 text-red-600 border-red-200': p.stock<10?'bg-amber-50 text-amber-700 border-amber-200':'bg-green-50 text-green-700 border-green-200'}">${p.stock} عدد</span></td>
      <td class="p-3"><span class="w-2 h-2 inline-block rounded-full ${p.status=='فعال'?'bg-green-500':'bg-gray-300'}"></span> ${p.status}</td>
      <td class="p-3"><div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
        <button onclick="openModal(${p.id})" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
        <a href="/admin/products/${p.id}/edit" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
        <button onclick="deleteProd(${p.id})" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-[#fff1f2] hover:text-[#ef4056] hover:border-[#fecdd3]"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
      </div></td>
    </tr>
  `).join('');
}
function openModal(id){ editId=id; const p=products.find(x=>x.id===id); document.getElementById('modalName').value=p.name; document.getElementById('modalPrice').value=p.price; document.getElementById('editModal').classList.remove('hidden'); document.getElementById('editModal').classList.add('flex'); }
function closeModal(){ document.getElementById('editModal').classList.add('hidden'); document.getElementById('editModal').classList.remove('flex'); }
function saveModal(){
  const name=document.getElementById('modalName').value; const price=document.getElementById('modalPrice').value;
  fetch(`/admin/products/${editId}`,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({name,price})})
    .then(()=>{ const p=products.find(x=>x.id===editId); p.name=name; p.price=parseInt(price); render(); closeModal(); showToast('ویرایش با AJAX انجام شد','success'); })
    .catch(()=>{ closeModal(); showToast('بروزرسانی دمو','success'); });
}
function deleteProd(id){ if(confirm('حذف شود؟')){ fetch(`/admin/products/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>{ showToast('حذف شد','success'); document.querySelector(`tr`); }).catch(()=> showToast('حذف دمو','success')); products.splice(products.findIndex(x=>x.id===id),1); render(); } }
document.getElementById('prodSearch').addEventListener('input',e=>{
  const v=e.target.value.toLowerCase();
  const filtered=products.filter(p=>p.name.toLowerCase().includes(v));
  document.getElementById('prodTable').innerHTML = filtered.map(p=>`<tr><td colspan="7" class="p-3">${p.name}</td></tr>`).join('') || '<tr><td colspan="7" class="p-8 text-center text-gray-400">یافت نشد</td></tr>';
  if(!v) render();
});
render();
</script>
@endpush
