@extends('layouts.app')
@section('title','تسویه حساب')
@section('content')
<div class="max-w-[1280px] mx-auto px-4 lg:px-8 mt-6">
    <div class="flex items-center gap-2 text-xs mb-6">
        <span class="w-7 h-7 rounded-full bg-[#ef4056] text-white flex items-center justify-center">1</span><span class="font-bold">اطلاعات ارسال</span>
        <span class="flex-1 h-[1px] bg-gray-200"></span>
        <span class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center">2</span><span class="text-gray-400">پرداخت</span>
        <span class="flex-1 h-[1px] bg-gray-200"></span>
        <span class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center">3</span><span class="text-gray-400">اتمام</span>
    </div>

    <form id="checkoutForm" class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 space-y-4">
            <div class="bg-white rounded-xl border p-5">
                <h3 class="font-bold text-sm mb-4 flex items-center gap-2"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> آدرس تحویل</h3>
                <div class="grid md:grid-cols-2 gap-3">
                    <input name="name" placeholder="نام و نام خانوادگی" value="علی محمدی" class="h-11 border rounded-lg px-3 text-sm outline-none focus:border-[#ef4056]/40 focus:ring-2 focus:ring-[#ef4056]/10">
                    <input name="phone" placeholder="شماره موبایل" value="09120000000" class="h-11 border rounded-lg px-3 text-sm ltr outline-none focus:border-[#ef4056]/40" dir="ltr">
                    <input name="postal" placeholder="کد پستی" class="h-11 border rounded-lg px-3 text-sm">
                    <select class="h-11 border rounded-lg px-3 text-sm"><option>تهران</option><option>اصفهان</option><option>مشهد</option></select>
                </div>
                <textarea name="address" rows="3" placeholder="آدرس دقیق" class="mt-3 w-full border rounded-lg p-3 text-sm outline-none focus:border-[#ef4056]/40">تهران، خیابان انقلاب، پلاک 123، واحد 5</textarea>
                <label class="flex items-center gap-2 mt-3 text-xs"><input type="checkbox" checked class="accent-[#ef4056]"> تحویل به همین آدرس</label>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h3 class="font-bold text-sm mb-4">شیوه پرداخت</h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 border-2 border-[#ef4056] bg-[#fff1f2] rounded-xl p-4 cursor-pointer">
                        <input type="radio" name="payment_method" value="online" checked class="accent-[#ef4056]">
                        <div class="flex-1"><div class="font-bold text-sm">پرداخت آنلاین</div><div class="text-xs text-gray-500">پرداخت از طریق درگاه شاپرک</div></div>
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4056"><rect x="2" y="7" width="20" height="12" rx="2"/><path d="M2 11h20"/></svg>
                    </label>
                    <label class="flex items-center gap-3 border rounded-xl p-4 cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="cod" class="accent-[#ef4056]">
                        <div class="flex-1"><div class="font-bold text-sm">پرداخت در محل</div><div class="text-xs text-gray-500">پرداخت هنگام تحویل</div></div>
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#62666d"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h3 class="font-bold text-sm mb-4">زمان ارسال</h3>
                <div class="grid md:grid-cols-3 gap-3 text-xs">
                    <label class="border-2 border-[#ef4056] bg-[#fff1f2] rounded-xl p-3 text-center cursor-pointer"><input type="radio" name="time" checked class="hidden"><div class="font-bold">فردا - 12 خرداد</div><div class="text-gray-500 mt-1">ساعت 9 تا 18 - رایگان</div></label>
                    <label class="border rounded-xl p-3 text-center cursor-pointer hover:bg-gray-50"><div class="font-bold">پس‌فردا</div><div class="text-gray-500 mt-1">ساعت 9 تا 18</div></label>
                    <label class="border rounded-xl p-3 text-center cursor-pointer hover:bg-gray-50"><div class="font-bold">13 خرداد</div><div class="text-gray-500 mt-1">ساعت 9 تا 18</div></label>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[360px] shrink-0">
            <div class="bg-white rounded-xl border p-5 sticky top-[90px]">
                <h3 class="font-bold text-sm mb-4">خلاصه سفارش — 3 کالا</h3>
                <div class="space-y-3 text-xs max-h-[200px] overflow-auto pr-1">
                    <div class="flex gap-3"><div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center shrink-0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div><div class="flex-1"><div class="font-medium">Galaxy S24 Ultra</div><div class="text-gray-500">تعداد 1</div></div><div class="font-bold">56,900,000</div></div>
                    <div class="flex gap-3"><div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center shrink-0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/></svg></div><div class="flex-1"><div class="font-medium">WH-1000XM5</div><div class="text-gray-500">تعداد 1</div></div><div class="font-bold">15,900,000</div></div>
                </div>
                <div class="border-t mt-4 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">جمع کل</span><span>72,800,000 تومان</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">هزینه ارسال</span><span>رایگان</span></div>
                    <div class="flex justify-between font-black text-base pt-2 border-t"><span>مبلغ قابل پرداخت</span><span>72,800,000 تومان</span></div>
                </div>
                <button type="submit" class="mt-4 w-full h-11 bg-[#ef4056] hover:bg-[#d32f44] text-white rounded-lg font-bold shadow-lg shadow-[#ef4056]/20 hover:-translate-y-0.5 transition">پرداخت و ثبت سفارش</button>
                <div class="text-[11px] text-gray-400 text-center mt-3">با ثبت سفارش، با <a href="/about" class="text-[#19bfd3]">قوانین و حریم خصوصی</a> موافقید</div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e){
  e.preventDefault();
  const btn=e.target.querySelector('button[type="submit"]');
  btn.disabled=true; btn.textContent='در حال پردازش...';
  fetch('/checkout',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({address:'تهران',payment_method:document.querySelector('input[name="payment_method"]:checked').value})})
  .then(r=>r.json()).then(j=>{
    showToast('سفارش با موفقیت ثبت شد!','success');
    setTimeout(()=> location.href='/checkout/success/1', 800);
  }).catch(()=>{
    showToast('سفارش ثبت شد (دمو)','success');
    setTimeout(()=> location.href='/checkout/success/1', 800);
  });
});
</script>
@endpush
