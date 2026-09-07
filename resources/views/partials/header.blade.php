<header class="site-header">
 <div class="header-main container">
  <a class="brand" href="{{ route('home') }}" aria-label="دیجینو"><span class="brand-bag"><x-icon name="bag"/></span><span><strong>دیجینو</strong><small>خرید هوشمند</small></span></a>
  <div class="search-wrap"><x-icon name="search"/><form action="{{ route('search') }}"><input id="global-search" name="q" value="{{ request('q') }}" autocomplete="off" placeholder="جستجو در محصولات، برندها و دسته‌بندی‌ها..."/></form><div id="search-suggestions" class="suggestions"></div></div>
  <div class="header-actions">
   @auth<a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('dashboard') }}"><x-icon name="user"/><span>{{ auth()->user()->name }}</span></a>@else<a href="{{ route('login') }}"><x-icon name="user"/><span>ورود / ثبت‌نام</span></a>@endauth
   <a href="{{ route('cart') }}" class="cart-link"><x-icon name="bag"/><span>سبد خرید</span><b id="cart-count">{{ array_sum(session('cart',[])) }}</b></a>
   <button class="mobile-menu" data-open="mobile-nav"><x-icon name="menu"/></button>
  </div>
 </div>
 <nav class="main-nav"><div class="container nav-inner">
  <button class="nav-category" data-open="category-pop"><x-icon name="menu"/> دسته‌بندی کالاها</button>
  <div class="nav-links"><a href="{{ route('category','digital') }}">کالای دیجیتال</a><a href="{{ route('category','mobile') }}">موبایل</a><a href="{{ route('category','accessory') }}">لوازم جانبی</a><a href="{{ route('category','laptop') }}">کامپیوتر و لپ‌تاپ</a><a href="{{ route('category','audio') }}">صوتی و تصویری</a><a href="{{ route('category','watch') }}">ساعت و پوشیدنی</a><a href="{{ route('category','gaming') }}">گیمینگ</a></div>
  <a class="sale-link" href="{{ route('products',['sort'=>'cheap']) }}">فروش ویژه</a>
 </div></nav>
</header>
<div class="drawer" id="mobile-nav"><button class="drawer-close" data-close><x-icon name="close"/></button><a class="brand" href="/">دیجینو</a><a href="/products">فروشگاه</a>@foreach(\App\Models\Category::where('is_active',1)->get() as $cat)<a href="{{ route('category',$cat) }}">{{ $cat->name }}</a>@endforeach<a href="/about">درباره دیجینو</a></div>
<div class="category-pop" id="category-pop">@foreach(\App\Models\Category::where('is_active',1)->get() as $cat)<a href="{{ route('category',$cat) }}"><x-icon name="box"/>{{ $cat->name }}<x-icon name="chevron"/></a>@endforeach</div>
<div class="scrim" data-close></div>