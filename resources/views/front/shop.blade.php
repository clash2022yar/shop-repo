@extends('layouts.app')
@section('title','کالای دیجیتال — دیجینو')
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4">
    <nav class="text-[11.5px] text-gray-500 flex items-center gap-1 mb-3">
        <a href="/" class="hover:text-gray-700">خانه</a> <span>›</span> <span>کالای دیجیتال</span> <span>›</span> <span class="text-gray-900 font-medium">کالای دیجیتال</span>
    </nav>
    <div class="flex items-center justify-between mb-4">
        <h1 class="font-extrabold text-lg">کالای دیجیتال</h1>
        <div class="text-xs text-gray-500">نمایش 1 تا 24 از 5234 کالا</div>
    </div>

    <div class="flex gap-4">
        <!-- Sidebar Filters -->
        <aside class="hidden lg:block w-[270px] shrink-0 space-y-4 sticky top-[110px] h-fit">
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-bold text-sm">فیلترهای محصولات</span>
                    <button onclick="clearFilters()" class="text-[11px] text-[#19bfd3] border border-[#19bfd3] rounded-full px-3 py-1 hover:bg-[#19bfd3] hover:text-white transition">پاک کردن همه</button>
                </div>

                <div class="space-y-4 text-[13px]">
                    <div>
                        <div class="font-bold mb-2 flex items-center justify-between cursor-pointer" onclick="this.nextElementSibling.classList.toggle('hidden')">دسته‌بندی <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></div>
                        <div class="space-y-2 pr-2 text-gray-600">
                            @php $cats=['موبایل (1287)','لپتاپ (823)','تبلت (347)','ساعت هوشمند (243)','هدفون و اسپیکر (982)','کنسول بازی (156)','لوازم جانبی موبایل (584)']; @endphp
                            @foreach($cats as $c)<label class="flex items-center justify-between cursor-pointer hover:text-gray-900"><span>{{ explode(' ', $c)[0] }}</span><span class="text-[11px] bg-gray-100 px-1.5 rounded">{{ trim(explode('(',$c)[1],')') }}</span></label>@endforeach
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="font-bold mb-2">برند</div>
                        <div class="relative mb-2"><input placeholder="جستجوی برند" class="w-full h-8 bg-gray-50 border rounded-lg pr-8 pl-2 text-xs"><svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                        <div class="space-y-1.5 max-h-40 overflow-auto pr-1">
                            <label class="flex items-center gap-2"><input type="checkbox" class="rounded"> سامسونگ <span class="mr-auto text-[11px] bg-gray-100 px-1 rounded">542</span></label>
                            <label class="flex items-center gap-2"><input type="checkbox"> اپل <span class="mr-auto text-[11px] bg-gray-100 px-1 rounded">421</span></label>
                            <label class="flex items-center gap-2"><input type="checkbox"> شیائومی <span class="mr-auto text-[11px] bg-gray-100 px-1 rounded">398</span></label>
                            <label class="flex items-center gap-2"><input type="checkbox"> انکر <span class="mr-auto text-[11px] bg-gray-100 px-1 rounded">320</span></label>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="font-bold mb-3">قیمت (تومان)</div>
                        <div class="px-2">
                            <input type="range" min="0" max="100000000" value="50000000" class="w-full accent-[#ef4056]" id="priceRange">
                            <div class="flex gap-2 mt-2">
                                <input id="minPrice" value="100,000,000" class="flex-1 h-8 border rounded-lg text-center text-xs">
                                <span class="text-xs flex items-center">تا</span>
                                <input id="maxPrice" value="100,000,000" class="flex-1 h-8 border rounded-lg text-center text-xs">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="font-bold mb-2">رنگ</div>
                        <div class="flex flex-wrap gap-2">
                            <button class="w-6 h-6 rounded-full bg-black border-2 border-white shadow"></button>
                            <button class="w-6 h-6 rounded-full bg-white border shadow"></button>
                            <button class="w-6 h-6 rounded-full bg-red-500"></button>
                            <button class="w-6 h-6 rounded-full bg-blue-500"></button>
                            <button class="w-6 h-6 rounded-full bg-green-500"></button>
                        </div>
                    </div>
                    <hr>
                    <label class="flex items-center justify-between"><span class="text-sm">فقط کالاهای موجود</span><input type="checkbox" class="toggle"></label>
                    <label class="flex items-center justify-between"><span class="text-sm">امکان خرید حضوری</span><input type="checkbox"></label>
                    <hr>
                    <div>
                        <div class="font-bold mb-2">امتیاز مشتریان</div>
                        <div class="space-y-1 text-xs">
                            <div class="flex items-center gap-2">4 ستاره و بیشتر <span class="text-amber-400">★★★★☆</span> <span class="mr-auto">2431</span></div>
                            <div class="flex items-center gap-2">3 ستاره و بیشتر <span class="text-amber-400">★★★☆☆</span> <span class="mr-auto">3201</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
            <!-- Sort & View -->
            <div class="bg-white rounded-xl border border-gray-100 p-3 flex flex-wrap items-center gap-3 mb-4 text-[13px]">
                <span class="font-bold flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg> مرتب‌سازی:</span>
                <button class="text-[#ef4056] font-bold">پربازدیدترین</button>
                <button class="text-gray-500 hover:text-gray-900">جدیدترین</button>
                <button class="text-gray-500 hover:text-gray-900">ارزان‌ترین</button>
                <button class="text-gray-500 hover:text-gray-900">گران‌ترین</button>
                <span class="mr-auto text-xs text-gray-500 hidden md:inline">نمایش 1 تا 24 از 5234 کالا</span>
                <select class="border rounded-lg h-8 text-xs px-2">
                    <option>پربازدیدترین</option><option>جدیدترین</option>
                </select>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="shopGrid"></div>

            <!-- Pagination -->
            <div class="flex justify-center mt-6 gap-1">
                <button class="w-8 h-8 rounded-lg bg-white border flex items-center justify-center hover:bg-gray-50">›</button>
                <button class="w-8 h-8 rounded-lg bg-[#ef4056] text-white font-bold">1</button>
                <button class="w-8 h-8 rounded-lg bg-white border hover:bg-gray-50">2</button>
                <button class="w-8 h-8 rounded-lg bg-white border hover:bg-gray-50">3</button>
                <button class="w-8 h-8 rounded-lg bg-white border hover:bg-gray-50">4</button>
                <button class="w-8 h-8 rounded-lg bg-white border hover:bg-gray-50">5</button>
                <span class="w-8 h-8 flex items-center justify-center">...</span>
                <button class="w-8 h-8 rounded-lg bg-white border hover:bg-gray-50">223</button>
                <button class="w-8 h-8 rounded-lg bg-white border flex items-center justify-center">‹</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const shopProducts = [
 {name:'Galaxy S24 Ultra',price:56900000,discount:12,brand:'سامسونگ',img:'',count:124},{name:'iPhone 15 Pro Max',price:72900000,discount:0,count:89},{name:'MacBook Air M2 2023',price:46500000,discount:7,count:74},{name:'WH-1000XM5',price:15900000,discount:9,count:65},{name:'Watch Series 9',price:23500000,discount:0,count:67},{name:'PlayStation 5 Slim',price:29900000,discount:6,count:118},{name:'Galaxy Tab S9 FE',price:18900000,discount:0,count:54},{name:'AirPods Pro 2 USB-C',price:11490000,discount:10,count:88},
 {name:'Redmi Note 13 Pro',price:15900000,discount:11,count:156},{name:'JBL Flip 6',price:7650000,discount:0,count:72},{name:'Soundcore Life Q35',price:6200000,discount:0,count:97},{name:'VivoBook 15 X1504',price:15500000,discount:8,count:56},
 {name:'24MR400-B',price:7900000,discount:0,count:80},{name:'PowerPort III 65W',price:2490000,discount:0,count:113},{name:'Mi Band 8',price:2190000,discount:6,count:99},{name:'EOS 2000D',price:23800000,discount:0,count:66},
 {name:'DUAL RTX 4060',price:18900000,discount:7,count:32},{name:'K552',price:2890000,discount:0,count:79},{name:'MX Master 3S',price:4950000,discount:0,count:82},{name:'HDD Toshiba 1TB',price:3150000,discount:5,count:99},
];
function shopCard(p){
  const final = p.discount? Math.round(p.price*(1-p.discount/100)):p.price;
  return `<div class="bg-white border border-gray-100 rounded-xl p-3 hover:shadow-xl hover:border-gray-200 hover:-translate-y-1 transition-all duration-300 group">
    <div class="relative">
      ${p.discount? `<span class="absolute top-0 left-0 bg-[#ef4056] text-white text-[11px] font-bold px-1.5 py-0.5 rounded">%${p.discount}</span>`: (Math.random()>0.6? `<span class="absolute top-0 left-0 bg-[#0aad64]/10 text-[#0aad64] border border-[#0aad64]/20 text-[10px] px-1.5 py-0.5 rounded-full">فروش ویژه</span>`:'')}
      <button class="absolute top-0 right-0 w-6 h-6 flex items-center justify-center text-gray-300 hover:text-[#ef4056]"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
      <div class="h-36 flex items-center justify-center bg-gray-50 rounded-lg group-hover:bg-white transition"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.2"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
    </div>
    <div class="mt-3 min-h-[44px]"><div class="text-[12.5px] font-medium leading-5 line-clamp-2 group-hover:text-[#ef4056] transition">${p.name}</div><div class="text-[11px] text-gray-400 truncate">${p.brand||'دیجینو'}</div></div>
    <div class="flex items-center gap-1 text-amber-400 text-[11px] mt-1">★★★★★ <span class="text-gray-400">(${p.count})</span></div>
    <div class="mt-2 text-left">
      ${p.discount? `<div class="text-[11px] text-gray-400 line-through">${p.price.toLocaleString('fa-IR')}</div>`:''}
      <div class="font-bold text-sm">${final.toLocaleString('fa-IR')} تومان</div>
    </div>
    <button onclick="addToCart('${p.name}',${final})" class="mt-2 w-full h-8 bg-white border border-gray-200 rounded-lg text-xs font-bold hover:bg-[#ef4056] hover:text-white hover:border-[#ef4056] transition">افزودن به سبد</button>
  </div>`;
}
document.getElementById('shopGrid').innerHTML = shopProducts.map(shopCard).join('');
function clearFilters(){ showToast('فیلترها پاک شد','info'); }
</script>
@endpush
