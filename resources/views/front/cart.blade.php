@extends('layouts.app')
@section('title','سبد خرید شما — دیجینو')
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4">
    <nav class="text-[11px] text-gray-500 mb-3">خانه › سبد خرید</nav>
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-lg">سبد خرید شما</h1>
        <span class="text-xs text-gray-500">3 کالا در سبد خرید</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-4 items-start">
        <!-- Items -->
        <div class="flex-1 w-full bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-4 border-b flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" checked class="accent-[#ef4056]"> انتخاب همه</label>
                <button onclick="clearCart()" class="text-gray-500 hover:text-[#ef4056] flex items-center gap-1"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> حذف موارد انتخاب‌شده</button>
            </div>
            <div id="cartItems" class="divide-y">
                <!-- JS will render -->
            </div>
            <div class="p-4 flex justify-start">
                <a href="/shop" class="h-9 px-6 border rounded-lg text-sm flex items-center gap-2 hover:bg-gray-50">ادامه خرید <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg></a>
            </div>
        </div>

        <!-- Summary -->
        <div class="w-full lg:w-[340px] shrink-0 bg-white rounded-xl border border-gray-100 p-4 sticky top-[90px] space-y-4">
            <h3 class="font-bold text-sm">خلاصه سفارش</h3>
            <div class="space-y-2 text-[13px]">
                <div class="flex justify-between"><span class="text-gray-500">قیمت کالاها (3)</span><span id="subtotal">84,290,000 تومان</span></div>
                <div class="flex justify-between"><span class="text-gray-500">تخفیف</span><span id="discount" class="text-[#ef4056]">0 تومان</span></div>
                <div class="flex justify-between"><span class="text-gray-500">هزینه ارسال</span><span class="text-gray-500">0 تومان</span></div>
            </div>
            <!-- Coupon -->
            <div class="flex gap-2">
                <input id="couponInput" placeholder="کد تخفیف" class="flex-1 h-9 border rounded-lg px-3 text-sm">
                <button onclick="applyCoupon()" class="h-9 px-4 bg-white border border-[#ef4056] text-[#ef4056] rounded-lg text-xs font-bold hover:bg-[#ef4056] hover:text-white transition">اعمال</button>
            </div>
            <div class="border-t pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold">مبلغ قابل پرداخت</span>
                    <span id="total" class="font-black text-[16px]">84,290,000 تومان</span>
                </div>
                <a href="/checkout" id="checkoutBtn" class="mt-4 w-full h-11 bg-[#ef4056] hover:bg-[#d32f44] text-white rounded-lg font-bold flex items-center justify-center gap-2 shadow-lg shadow-[#ef4056]/20 hover:shadow-xl hover:-translate-y-0.5 transition">ادامه فرآیند خرید</a>
                <div class="mt-4 space-y-3 text-[11.5px]">
                    <div class="flex gap-2"><div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#62666d"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div><div><div class="font-bold">ارسال سریع</div><div class="text-gray-500">ارسال در سریع‌ترین زمان</div></div></div>
                    <div class="flex gap-2"><div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><div><div class="font-bold">ضمانت اصالت کالا</div><div class="text-gray-500">کالای اصلی با گارانتی معتبر</div></div></div>
                    <div class="flex gap-2"><div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div><div class="font-bold">پشتیبانی 24/7</div><div class="text-gray-500">در هر زمان پاسخگوی شما هستیم</div></div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maybe also like -->
    <section class="mt-6 bg-white rounded-xl border p-4">
        <div class="flex items-center justify-between mb-4"><h2 class="font-bold text-sm">شاید به این‌ها هم علاقه‌مند باشید</h2><a href="#" class="text-xs text-[#19bfd3]">مشاهده همه</a></div>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3" id="alsoLike"></div>
    </section>
</div>
@endsection
@push('scripts')
<script>
let cart = [
  {id:1,name:'گوشی موبایل سامسونگ Galaxy S24 Ultra',price:56900000,qty:1,img:'',spec:'رنگ: تیتانیوم مشکی - حافظه: 512 گیگابایت'},
  {id:2,name:'هدفون WH-1000XM5',price:15900000,qty:1,img:'',spec:'رنگ: مشکی'},
  {id:3,name:'ایرپادز پرو 2 اپل USB-C',price:11490000,qty:1,img:'',spec:'رنگ: سفید'},
];
function renderCart(){
  const container=document.getElementById('cartItems');
  container.innerHTML = cart.map((it,idx)=>`
    <div class="p-4 flex gap-4 group hover:bg-gray-50/50 transition">
      <label class="pt-2"><input type="checkbox" checked class="accent-[#ef4056]"></label>
      <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center shrink-0 border"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
      <div class="flex-1">
        <div class="font-medium text-[13px] leading-5">${it.name}</div>
        <div class="text-[11px] text-gray-500 mt-1">${it.spec}</div>
        <div class="flex items-center gap-3 mt-3">
          <div class="flex items-center border rounded-lg overflow-hidden h-8">
            <button onclick="changeQty(${idx},1)" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100">+</button>
            <span class="w-10 text-center text-sm font-medium border-x">${it.qty}</span>
            <button onclick="changeQty(${idx},-1)" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100">−</button>
          </div>
          <button onclick="removeItem(${idx})" class="w-8 h-8 border rounded-lg flex items-center justify-center text-gray-400 hover:text-[#ef4056] hover:border-[#ef4056]/20 hover:bg-[#fff1f2] transition"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
          <span class="mr-auto font-bold text-sm">${(it.price*it.qty).toLocaleString('fa-IR')} تومان</span>
        </div>
      </div>
      <label class="pt-2 hidden lg:block"><input type="checkbox" checked class="accent-[#ef4056] w-4 h-4"></label>
    </div>
  `).join('');
  updateTotals();
  updateCartBadge();
}
function changeQty(idx,delta){
  cart[idx].qty = Math.max(1, cart[idx].qty + delta);
  renderCart();
  fetch(`/cart/update/${cart[idx].id}`,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({quantity:cart[idx].qty})}).catch(()=>{});
  showToast('تعداد بروز شد','success');
}
function removeItem(idx){
  cart.splice(idx,1);
  renderCart();
  fetch(`/cart/remove/${idx}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).catch(()=>{});
  showToast('از سبد حذف شد','info');
}
function clearCart(){ if(confirm('همه حذف شود؟')){ cart=[]; renderCart(); showToast('سبد خالی شد','info'); } }
function updateTotals(){
  const subtotal = cart.reduce((s,i)=>s+i.price*i.qty,0);
  document.getElementById('subtotal').textContent = subtotal.toLocaleString('fa-IR')+' تومان';
  document.getElementById('total').textContent = subtotal.toLocaleString('fa-IR')+' تومان';
  if(cart.length===0) document.getElementById('checkoutBtn').classList.add('opacity-50','pointer-events-none');
  else document.getElementById('checkoutBtn').classList.remove('opacity-50','pointer-events-none');
}
function updateCartBadge(){ document.getElementById('cart-count').textContent = cart.reduce((s,i)=>s+i.qty,0) || 0; }
function applyCoupon(){
  const code=document.getElementById('couponInput').value.trim();
  if(!code) return showToast('کد را وارد کنید','error');
  fetch('/cart/apply-coupon',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({coupon:code})})
    .then(r=>r.json().then(j=>({ok:r.ok,j})) ).then(({ok,j})=>{
      if(ok){ showToast(j.message,'success'); document.getElementById('discount').textContent = j.discount + '% تخفیف';}
      else showToast(j.message,'error');
    }).catch(()=> showToast('کوپن اعمال شد (دمو)','success'));
}
renderCart();
const also=[{name:'Sony PlayStation 5 Slim',price:29900000,count:118},{name:'JBL Charge 5',price:8900000,count:65},{name:'Galaxy Tab S9 FE',price:18900000,count:54},{name:'MacBook Air M2 2023',price:46500000,count:74},{name:'Watch Series 9',price:23500000,count:67},{name:'iPhone 15 Pro Max',price:72900000,count:89}];
document.getElementById('alsoLike').innerHTML = also.map(p=>`
  <div class="border rounded-xl p-3 hover:shadow-lg transition group">
    <div class="h-24 bg-gray-50 rounded-lg flex items-center justify-center"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
    <div class="text-xs mt-2 line-clamp-2 font-medium group-hover:text-[#ef4056]">${p.name}</div>
    <div class="font-bold text-sm mt-1">${p.price.toLocaleString('fa-IR')} تومان</div>
    <div class="text-amber-400 text-[11px] mt-1">★★★★★ <span class="text-gray-400">(${p.count})</span></div>
  </div>`).join('');
</script>
@endpush
