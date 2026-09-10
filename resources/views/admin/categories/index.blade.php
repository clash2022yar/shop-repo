@extends('layouts.admin')
@section('title','دسته‌بندی‌ها')
@section('page_title','دسته‌بندی‌ها')
@section('content')
<div class="flex justify-between items-center mb-4">
    <div class="flex gap-2"><input placeholder="جستجوی دسته..." class="h-9 w-64 border rounded-lg px-3 text-sm"><button class="h-9 px-4 border rounded-lg text-sm hover:bg-gray-50">جستجو</button></div>
    <button onclick="openCatModal()" class="h-9 px-4 bg-[#ef4056] text-white rounded-lg text-sm font-bold">+ افزودن دسته</button>
</div>
<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500"><tr><th class="p-3 text-right">نام</th><th class="p-3 text-right">اسلاگ</th><th class="p-3 text-right">تعداد محصول</th><th class="p-3 text-right">وضعیت</th><th class="p-3"></th></tr></thead>
        <tbody class="divide-y">
            @php $cats=[['موبایل','mobile',1287],['لپتاپ','laptop',823],['تبلت','tablet',347],['ساعت هوشمند','smartwatch',243],['هدفون و اسپیکر','audio',982]]; @endphp
            @foreach($cats as $c)
            <tr class="hover:bg-gray-50 group">
                <td class="p-3 font-medium flex items-center gap-2"><div class="w-8 h-8 bg-gray-50 rounded-lg border flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div> {{ $c[0] }}</td>
                <td class="p-3 font-mono text-xs text-gray-500">{{ $c[1] }}</td>
                <td class="p-3"><span class="bg-gray-100 px-2 py-1 rounded-full text-xs">{{ $c[2] }}</span></td>
                <td class="p-3"><span class="bg-green-50 text-green-700 border border-green-200 px-2 py-1 rounded-full text-xs">فعال</span></td>
                <td class="p-3"><div class="flex gap-1 opacity-0 group-hover:opacity-100 transition"><button class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button onclick="if(confirm('حذف؟')) showToast('حذف شد','success')" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-[#fff1f2] hover:text-[#ef4056]"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></div></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="catModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <h3 class="font-bold">افزودن دسته (Modal + AJAX)</h3>
        <form class="mt-4 space-y-3" onsubmit="event.preventDefault(); fetch('/admin/categories',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({name:this.catName.value})}).then(()=>{showToast('دسته ساخته شد','success'); closeCatModal();});">
            <input name="catName" placeholder="نام دسته" required class="w-full h-10 border rounded-lg px-3 text-sm">
            <input placeholder="اسلاگ (خودکار)" class="w-full h-10 border rounded-lg px-3 text-sm bg-gray-50" disabled>
            <div class="flex gap-2 pt-2"><button type="submit" class="flex-1 h-10 bg-[#ef4056] text-white rounded-lg font-bold">ذخیره</button><button type="button" onclick="closeCatModal()" class="flex-1 h-10 border rounded-lg">انصراف</button></div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function openCatModal(){document.getElementById('catModal').classList.remove('hidden'); document.getElementById('catModal').classList.add('flex');}
function closeCatModal(){document.getElementById('catModal').classList.add('hidden'); document.getElementById('catModal').classList.remove('flex');}
</script>
@endpush
