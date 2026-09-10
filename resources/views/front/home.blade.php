@extends('layouts.app')
@section('title','دیجینو | فروشگاه اینترنتی تخصصی کالای دیجیتال')
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4 space-y-6">

    <!-- Hero Slider -->
    <div class="relative bg-[#f8f9fa] rounded-2xl overflow-hidden h-[340px] lg:h-[380px] flex items-center">
        <div id="heroSlider" class="w-full h-full relative">
            <div class="hero-slide absolute inset-0 flex items-center px-6 lg:px-12 transition-opacity duration-700 opacity-100">
                <div class="flex-1 text-right z-10">
                    <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-3 py-1 text-[11px] text-gray-600 mb-4">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> جدیدترین محصولات 1403
                    </div>
                    <h1 class="text-[22px] lg:text-[28px] font-extrabold leading-9 text-[#23254e]">جدیدترین کالای دیجیتال<br>با بهترین قیمت</h1>
                    <p class="text-[13px] text-[#62666d] mt-3 max-w-[420px]">گوشی، موبایل، لپتاپ، ساعت هوشمند و بیشتر...</p>
                    <a href="/shop" class="inline-flex items-center gap-2 mt-6 bg-[#ef4056] hover:bg-[#d32f44] text-white px-6 py-3 rounded-lg text-sm font-bold shadow-lg shadow-[#ef4056]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        مشاهده و خرید
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="flex-1 hidden lg:flex items-center justify-center gap-3">
                    <img src="/images/products/iphone.png" onerror="this.style.display='none'" class="w-28 drop-shadow-2xl animate-[slideUp_0.8s_ease-out] hidden" alt="">
                    <!-- Fallback CSS illustration -->
                    <div class="relative">
                        <div class="w-[260px] h-[180px] bg-gradient-to-br from-[#6366f1] via-[#8b5cf6] to-[#ec4899] rounded-3xl shadow-2xl flex items-center justify-center text-white/90 rotate-1 hover:rotate-0 transition duration-700">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.2" class="opacity-80"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        </div>
                        <div class="absolute -right-8 -bottom-4 w-20 h-20 bg-white rounded-2xl shadow-xl flex items-center justify-center border border-gray-100">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#23254e" stroke-width="1.6"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                        </div>
                        <div class="absolute -left-6 top-4 w-16 h-16 bg-black rounded-2xl shadow-xl flex items-center justify-center text-white font-mono text-[10px] leading-none">10<br>08</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dots -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
            <span class="w-6 h-1.5 bg-[#ef4056] rounded-full"></span><span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span><span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span><span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
        </div>
        <button class="absolute right-4 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 backdrop-blur rounded-full shadow flex items-center justify-center hover:bg-white transition"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></button>
        <button class="absolute left-4 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 backdrop-blur rounded-full shadow flex items-center justify-center hover:bg-white transition"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg></button>
    </div>

    <!-- Services -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3 justify-center border-l border-gray-100 last:border-0">
            <div class="w-10 h-10 rounded-full bg-[#f0f0f1] flex items-center justify-center text-[#62666d]"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="12" cy="14" r="2"/><path d="M12 12v2"/></svg></div>
            <div><div class="text-[12px] font-bold">پشتیبانی 24/7</div><div class="text-[11px] text-gray-500">در هر زمان پاسخگوی شما هستیم</div></div>
        </div>
        <div class="flex items-center gap-3 justify-center border-l border-gray-100">
            <div class="w-10 h-10 rounded-full bg-[#f0f0f1] flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
            <div><div class="text-[12px] font-bold">ارسال سریع</div><div class="text-[11px] text-gray-500">ارسال در سریع‌ترین زمان</div></div>
        </div>
        <div class="flex items-center gap-3 justify-center border-l border-gray-100">
            <div class="w-10 h-10 rounded-full bg-[#f0f0f1] flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"/><path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"/></svg></div>
            <div><div class="text-[12px] font-bold">7 روز ضمانت بازگشت</div><div class="text-[11px] text-gray-500">بازگشت کالا و وجه پرداختی</div></div>
        </div>
        <div class="flex items-center gap-3 justify-center">
            <div class="w-10 h-10 rounded-full bg-[#f0f0f1] flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg></div>
            <div><div class="text-[12px] font-bold">ضمانت اصالت کالا</div><div class="text-[11px] text-gray-500">کالای اصلی با گارانتی معتبر</div></div>
        </div>
    </div>

    <!-- Categories -->
    <section class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-sm">دسته‌بندی‌های محبوب</h2>
            <a href="/shop" class="text-[12px] text-[#19bfd3] hover:text-[#0ea5e9]">مشاهده همه</a>
        </div>
        <div class="grid grid-cols-4 md:grid-cols-8 lg:grid-cols-10 gap-4">
            @php $cats = ['موبایل','لپتاپ','هدفون و اسپیکر','ساعت هوشمند','کنسول بازی','تبلت','لوازم جانبی','کارت حافظه','دوربین و تجهیزات','مانیتور']; @endphp
            @foreach($cats as $i=>$c)
            <a href="/shop?category={{ $i }}" class="group flex flex-col items-center gap-2">
                <div class="w-[84px] h-[84px] rounded-full bg-[#f5f5f7] border border-gray-100 flex items-center justify-center group-hover:border-[#ef4056]/20 group-hover:bg-[#fff1f2] transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#3f4064" stroke-width="1.4" class="group-hover:stroke-[#ef4056] transition">
                        @if($i==0)<rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/> @elseif($i==1)<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/> @elseif($i==2)<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/> @else<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 2v5M8 2v5"/> @endif
                    </svg>
                </div>
                <span class="text-[11.5px] text-center leading-4 font-medium group-hover:text-[#ef4056] transition">{{ $c }}</span>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Special Offers -->
    <section class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-sm flex items-center gap-2"><span class="w-1 h-4 bg-[#ef4056] rounded-full"></span> پیشنهادهای ویژه</h2>
            <a href="/shop?on_sale=1" class="text-[12px] text-[#19bfd3]">مشاهده همه</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3" id="specialOffers">
            <!-- Product cards via JS or Blade -->
        </div>
    </section>

    <!-- Two Banners -->
    <div class="grid md:grid-cols-2 gap-4">
        <a href="/shop?category=audio" class="relative bg-[#ffe9ec] rounded-xl p-6 flex items-center justify-between overflow-hidden group h-[160px] border border-[#ffd1d7] hover:shadow-lg transition duration-500">
            <div class="z-10">
                <div class="text-[11px] text-[#ef4056] font-bold">تخفیف ویژه</div>
                <div class="font-extrabold text-[15px] mt-1">انواع هدفون و هندزفری</div>
                <div class="text-sm text-[#62666d]">تا 30% تخفیف</div>
                <span class="inline-flex mt-3 bg-white text-[#ef4056] text-xs font-bold px-4 py-1.5 rounded-full shadow group-hover:bg-[#ef4056] group-hover:text-white transition">مشاهده محصولات</span>
            </div>
            <div class="absolute left-6 top-1/2 -translate-y-1/2 w-28 h-28 bg-white rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition duration-500">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#1f2937" stroke-width="1.4"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
            </div>
            <div class="absolute inset-0 bg-gradient-to-l from-transparent to-white/20 opacity-0 group-hover:opacity-100 transition"></div>
        </a>
        <a href="/shop?category=smartwatch" class="relative bg-[#e6f7f5] rounded-xl p-6 flex items-center justify-between overflow-hidden group h-[160px] border border-[#c7ebe7] hover:shadow-lg transition duration-500">
            <div class="z-10">
                <div class="text-[11px] text-[#0aad64] font-bold">فروش ویژه</div>
                <div class="font-extrabold text-[15px] mt-1">ساعت‌های هوشمند</div>
                <div class="text-sm text-[#62666d]">تا 25% تخفیف</div>
                <span class="inline-flex mt-3 bg-white text-[#0aad64] text-xs font-bold px-4 py-1.5 rounded-full shadow group-hover:bg-[#0aad64] group-hover:text-white transition">مشاهده محصولات</span>
            </div>
            <div class="absolute left-6 top-1/2 -translate-y-1/2 w-28 h-28 bg-white rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition duration-500">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="1.6"><rect x="6" y="2" width="12" height="20" rx="4"/><path d="M12 18h.01"/><path d="M8 6h8"/><circle cx="12" cy="10" r="1" fill="#ef4056" stroke="none"/><circle cx="10" cy="12" r="1" fill="#19bfd3" stroke="none"/><circle cx="14" cy="12" r="1" fill="#f59e0b" stroke="none"/><circle cx="12" cy="14" r="1" fill="#10b981" stroke="none"/></svg>
            </div>
        </a>
    </div>

    <!-- Bestsellers -->
    <section class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-sm">پرفروش‌ترین محصولات</h2>
            <a href="/shop?sort=popular" class="text-[12px] text-[#19bfd3]">مشاهده همه</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3" id="bestsellers"></div>
    </section>

    <!-- Brands -->
    <section class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-sm">برندهای محبوب</h2>
            <a href="#" class="text-[12px] text-[#19bfd3]">مشاهده همه</a>
        </div>
        <div class="grid grid-cols-4 md:grid-cols-8 gap-3">
            @php $brands=['Apple','SAMSUNG','mi','سونی','ASUS','hp','لنووا']; @endphp
            @foreach($brands as $b)
            <div class="h-20 border border-gray-100 rounded-xl flex items-center justify-center hover:border-gray-200 hover:shadow-md transition bg-white group">
                <span class="font-black text-sm tracking-tight group-hover:scale-105 transition {{$loop->first? 'text-black': ($loop->index==1? 'text-[#1428a0]': ($loop->index==2? 'text-orange-500': 'text-gray-700')) }}">{{$b}}</span>
            </div>
            @endforeach
            <div class="h-20 border border-gray-100 rounded-xl flex items-center justify-center hover:shadow-md transition bg-white">
                <div class="grid grid-cols-2 gap-1 w-8 h-8"><span class="bg-[#f25022]"></span><span class="bg-[#7fba00]"></span><span class="bg-[#00a4ef]"></span><span class="bg-[#ffb900]"></span></div>
            </div>
        </div>
    </section>

    <!-- Magazine -->
    <section class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-sm">مجله دیجینو</h2>
            <a href="#" class="text-[12px] text-[#19bfd3]">مشاهده همه</a>
        </div>
        <div class="grid md:grid-cols-3 gap-4">
            @php $mags=[['مقایسه ایرپادز 2 و تنکنو پاپ 2 مورد','کنار گذاشتن پیشنهادی برای خرید اقتصادی، مقاومت بالایی در برابر گرد و غبار دارد','1403/03/05'],['بهترین لپتاپ‌ها برای دانشجویان','معرفی بهترین لپتاپ‌هایی که برای دانشجویان و پروژه‌های دانشجویی مناسب هستند','1403/03/08'],['راهنمای خرید گوشی موبایل در سال 1403','برای خرید گوشی موبایلی که به نیازهای شما باشد تیم ما در این مقاله نکات مهمی را با شما به اشتراک می‌گذارد','1403/03/10']]; @endphp
            @foreach($mags as $m)
            <a href="#" class="group border border-gray-100 rounded-xl overflow-hidden hover:shadow-lg hover:-translate-y-1 transition duration-300">
                <div class="h-36 bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#6366f1]/20 to-[#ec4899]/20"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition"></div>
                </div>
                <div class="p-4">
                    <div class="font-bold text-[13px] leading-5 line-clamp-1 group-hover:text-[#ef4056] transition">{{$m[0]}}</div>
                    <div class="text-[12px] text-gray-500 leading-6 mt-2 line-clamp-2">{{$m[1]}}</div>
                    <div class="text-[11px] text-gray-400 mt-3">{{$m[2]}}</div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
@push('scripts')
<script>
const mockProducts = [
  {name:'PlayStation 5',price:29900000,discount:6,rating:4.8,count:118,cat:'کنسول بازی'},
  {name:'WH-1000XM5',price:15900000,discount:9,rating:4.6,count:65,cat:'هدفون سونی'},
  {name:'Redmi Watch 4',price:4790000,discount:10,rating:4.5,count:89,cat:'ساعت هوشمند'},
  {name:'MacBook Air M2',price:46500000,discount:7,rating:4.9,count:74,cat:'لپتاپ اپل'},
  {name:'Galaxy S24 Ultra',price:56900000,discount:12,rating:4.7,count:124,cat:'گوشی سامسونگ'},
  {name:'ایرپادز 2',price:11490000,discount:10,rating:4.6,count:88,cat:'اپل'},
  {name:'لپتاپ 15s',price:21900000,discount:0,rating:4.3,count:74,cat:'لپتاپ'},
  {name:'JBL Charge 5',price:8800000,discount:0,rating:4.7,count:65,cat:'اسپیکر'},
  {name:'Apple Watch Series 9',price:9900000,discount:0,rating:4.8,count:87,cat:'اپل'},
  {name:'Redmi Note 13 Pro',price:15900000,discount:0,rating:4.4,count:156,cat:'شیائومی'},
  {name:'گلکسی A55',price:23500000,discount:0,rating:4.5,count:98,cat:'سامسونگ'},
  {name:'آیفون 15 پرو مکس',price:72900000,discount:0,rating:4.9,count:234,cat:'اپل'},
];
function card(p){
  const hasDiscount = p.discount>0;
  const final = hasDiscount ? Math.round(p.price*(1-p.discount/100)) : p.price;
  return `<div class="border border-gray-100 rounded-xl p-3 hover:shadow-lg hover:border-gray-200 hover:-translate-y-1 transition-all duration-300 group bg-white relative overflow-hidden">
    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
      ${hasDiscount? `<span class="bg-[#ef4056] text-white text-[11px] font-bold px-1.5 py-0.5 rounded">%${p.discount}</span>`: ''}
      <button onclick="toggleWishlist(this)" class="w-7 h-7 bg-white shadow rounded-full flex items-center justify-center text-gray-400 hover:text-[#ef4056] transition"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
    </div>
    <a href="/product/${encodeURIComponent(p.name)}" class="block">
      <div class="h-28 flex items-center justify-center bg-gray-50 rounded-lg mb-3 group-hover:bg-white transition">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
      </div>
      <div class="text-[12px] leading-5 font-medium line-clamp-2 min-h-[40px] group-hover:text-[#ef4056] transition">${p.name}</div>
      <div class="text-[11px] text-gray-400">${p.cat}</div>
      <div class="flex items-center gap-1 mt-2 text-amber-400 text-[11px]">${'★'.repeat(5)} <span class="text-gray-400">(${p.count})</span></div>
      <div class="mt-2">
        ${hasDiscount? `<div class="text-[11px] text-gray-400 line-through">${p.price.toLocaleString('fa-IR')} تومان</div>`: ''}
        <div class="font-bold text-[13px]">${final.toLocaleString('fa-IR')} تومان</div>
      </div>
    </a>
    <button onclick="addToCart('${p.name}',${final})" class="mt-3 w-full h-8 bg-[#f5f5f7] hover:bg-[#ef4056] hover:text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1">افزودن به سبد <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></button>
  </div>`;
}
document.getElementById('specialOffers').innerHTML = mockProducts.slice(0,6).map(card).join('');
document.getElementById('bestsellers').innerHTML = mockProducts.slice(6,12).map(card).join('');

// Simple hero auto
let heroIdx=0;
setInterval(()=>{
  document.querySelector('.hero-slide')?.classList.add('opacity-0');
  setTimeout(()=>document.querySelector('.hero-slide')?.classList.remove('opacity-0'),200);
},5000);
</script>
@endpush
