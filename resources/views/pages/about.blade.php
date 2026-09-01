@extends('layouts.app')

@section('title', 'درباره دیجی‌نو')
@section('meta_description', 'دیجی‌نو فروشگاه اینترنتی کالای دیجیتال است؛ با ضمانت اصالت کالا، قیمت شفاف و پشتیبانی واقعی.')

@section('content')
<div class="container space-y-4">

    {{-- ══════════ intro ══════════ --}}
    <section class="overflow-hidden rounded-card bg-white shadow-card" data-reveal>
        <div class="bg-gradient-to-l from-brand-600 to-brand-500 px-6 py-10 text-center text-white lg:py-14">
            <img src="{{ asset('images/logo/mark.svg') }}" alt="" class="mx-auto h-16 w-16 rounded-2xl bg-white/10 p-2">
            <h1 class="mt-4 text-xl font-extrabold lg:text-2xl">دیجی‌نو، خرید هوشمند کالای دیجیتال</h1>
            <p class="mx-auto mt-3 max-w-2xl text-2xs leading-8 text-white/85 lg:text-sm">
                ما دیجی‌نو را ساختیم تا خرید آنلاین کالای دیجیتال ساده، شفاف و قابل اعتماد باشد؛
                جایی که قیمت‌ها واقعی‌اند، مشخصات فنی دقیق‌اند و پشتیبانی، انسان واقعی است نه پیام خودکار.
            </p>
        </div>

        <div class="grid grid-cols-2 divide-x divide-x-reverse divide-ink-100 lg:grid-cols-4">
            @php
                $items = [
                    ['label' => 'کالای فعال', 'value' => $stats['products'], 'icon' => 'box'],
                    ['label' => 'مشتری راضی', 'value' => $stats['customers'], 'icon' => 'users'],
                    ['label' => 'سفارش موفق', 'value' => $stats['orders'], 'icon' => 'receipt'],
                    ['label' => 'برند معتبر', 'value' => $stats['brands'], 'icon' => 'tag'],
                ];
            @endphp
            @foreach($items as $item)
                <div class="flex flex-col items-center gap-2 p-6 text-center">
                    <x-icon :name="$item['icon']" class="h-7 w-7 text-brand-500" />
                    <span class="text-xl font-extrabold text-ink-900" data-count-to="{{ $item['value'] }}">۰</span>
                    <span class="text-2xs text-ink-500">{{ $item['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ══════════ story ══════════ --}}
    <section class="section" data-reveal>
        <h2 class="section-title mb-4">داستان ما</h2>
        <div class="space-y-4 text-[0.8125rem] leading-8 text-ink-600">
            <p>
                دیجی‌نو از یک پرسش ساده شروع شد: چرا خرید یک گوشی یا لپ‌تاپ باید این‌قدر سخت باشد؟
                قیمت‌های متفاوت در سایت‌های مختلف، مشخصات ناقص، ضمانت‌های مبهم و پشتیبانی‌ای که پاسخ نمی‌دهد.
                تصمیم گرفتیم فروشگاهی بسازیم که همهٔ این‌ها را درست انجام دهد.
            </p>
            <p>
                امروز دیجی‌نو مجموعه‌ای گسترده از کالاهای دیجیتال، لوازم خانگی و کالای ورزشی را با
                ضمانت اصالت، قیمت شفاف و امکان بازگشت هفت‌روزه در اختیار شما می‌گذارد. هر کالایی که
                در دیجی‌نو می‌بینید، پیش از ارسال از نظر سلامت فیزیکی و اصالت بررسی می‌شود.
            </p>
        </div>
    </section>

    {{-- ══════════ values ══════════ --}}
    <section class="section" data-reveal>
        <h2 class="section-title mb-4">ارزش‌های ما</h2>
        <div class="grid gap-4 stagger sm:grid-cols-2 lg:grid-cols-3">
            @php
                $values = [
                    ['icon' => 'shield-check', 'title' => 'اصالت بی‌قید و شرط', 'text' => 'هر کالای دیجی‌نو اصل است. اگر خلاف آن ثابت شود، کل مبلغ را بازمی‌گردانیم.'],
                    ['icon' => 'scale', 'title' => 'قیمت شفاف', 'text' => 'قیمت‌ها بدون هزینه پنهان اعلام می‌شوند؛ آنچه می‌بینید همان است که می‌پردازید.'],
                    ['icon' => 'headset', 'title' => 'پشتیبانی واقعی', 'text' => 'کارشناسان ما پاسخ می‌دهند، نه ربات‌ها. تیکت‌های شما را انسان می‌خواند.'],
                    ['icon' => 'rotate-left', 'title' => 'بازگشت آسان', 'text' => 'تا هفت روز پس از تحویل، بدون نیاز به توضیح می‌توانید کالا را بازگردانید.'],
                    ['icon' => 'truck', 'title' => 'ارسال سریع', 'text' => 'سفارش‌ها در سریع‌ترین زمان ممکن آماده و به سراسر کشور ارسال می‌شوند.'],
                    ['icon' => 'lock', 'title' => 'حریم خصوصی', 'text' => 'اطلاعات شما فروخته یا در اختیار اشخاص ثالث تبلیغاتی قرار نمی‌گیرد.'],
                ];
            @endphp
            @foreach($values as $value)
                <article class="group rounded-card border border-ink-100 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-card-hover">
                    <span class="icon-tile mb-3 transition-transform duration-300 group-hover:scale-110">
                        <x-icon :name="$value['icon']" class="h-6 w-6" />
                    </span>
                    <h3 class="text-sm font-bold text-ink-900">{{ $value['title'] }}</h3>
                    <p class="mt-2 text-2xs leading-7 text-ink-500">{{ $value['text'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ══════════ license ══════════ --}}
    <section id="license" class="section scroll-mt-32" data-reveal>
        <h2 class="section-title mb-4 flex items-center gap-2">
            <x-icon name="shield" class="h-5 w-5 text-brand-500" />
            حقوق مالکیت فکری و شرایط استفاده از طراحی
        </h2>

        <div class="space-y-4 rounded-card bg-ink-50 p-5 text-[0.8125rem] leading-8 text-ink-700">
            <p>
                تمامی حقوق مادی و معنوی این وب‌سایت — شامل نام و نشان تجاری «دیجی‌نو»، طراحی رابط کاربری،
                چیدمان صفحات، سامانهٔ آیکن‌ها، متن‌ها، تصاویر تولیدشده و کد منبع (سمت کاربر و سمت سرور) —
                متعلق به پدیدآورندهٔ آن، <span class="font-bold text-ink-900">یارمحمدی</span>، است و تحت
                حمایت قوانین حمایت از حقوق مؤلفان، مصنفان و هنرمندان قرار دارد.
            </p>

            <p>
                خواهشمندیم به این حقوق احترام بگذارید. بدون کسب <span class="font-bold">اجازهٔ کتبی و پیشین</span>
                از پدیدآورنده، انجام هیچ‌یک از موارد زیر مجاز نیست:
            </p>

            <ul class="space-y-2.5 ps-1">
                @foreach([
                    'کپی‌برداری، تکثیر یا بازتولید کامل یا بخشی از طراحی، کد و محتوای این وب‌سایت؛',
                    'استفاده از این طراحی به‌عنوان قالب یا پایهٔ یک پروژهٔ تجاری، آموزشی یا شخصی دیگر؛',
                    'فروش، اجاره، توزیع مجدد یا انتشار عمومی فایل‌های پروژه در هر بستری؛',
                    'حذف، تغییر یا پنهان‌سازی نشان‌های مالکیت، اعتبار پدیدآورنده و همین متن حقوقی؛',
                    'مهندسی معکوس به‌قصد ساخت اثری مشتق‌شده که به‌طور اساسی مشابه این طراحی باشد.',
                ] as $rule)
                    <li class="flex items-start gap-2">
                        <x-icon name="minus" class="mt-1.5 h-3 w-3 shrink-0 text-brand-500" />
                        <span>{{ $rule }}</span>
                    </li>
                @endforeach
            </ul>

            <p>
                استفادهٔ شخصی و غیرتجاری برای مطالعه و یادگیری — مانند بررسی ساختار کد به‌قصد آموزش —
                با ذکر نام پدیدآورنده بلامانع است. برای هر کاربرد فراتر از آن، پیش از هر اقدامی
                از طریق <a href="{{ route('contact') }}" class="font-bold text-brand-500 hover:underline">صفحهٔ تماس با ما</a>
                درخواست خود را مطرح کنید؛ با کمال میل بررسی می‌کنیم.
            </p>

            <p class="rounded-field border border-ink-200 bg-white p-4 text-2xs leading-7 text-ink-600">
                <span class="font-bold text-ink-800">خلاصه:</span>
                این اثر آزادانه قابل کپی‌برداری نیست. لطفاً بدون اجازهٔ کتبی، از آن نسخه‌برداری یا بازنشر نکنید.
                احترام شما به کار پدیدآورنده، بهترین پشتیبانی از ادامهٔ چنین پروژه‌هایی است.
            </p>
        </div>
    </section>

    {{-- ══════════ cta ══════════ --}}
    <section class="section flex flex-wrap items-center justify-between gap-4" data-reveal>
        <div>
            <h2 class="text-sm font-extrabold text-ink-900">سؤالی دارید؟</h2>
            <p class="mt-1 text-2xs text-ink-500">تیم پشتیبانی دیجی‌نو آمادهٔ پاسخ‌گویی به شماست.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('contact') }}" class="btn-primary">
                <x-icon name="mail" class="h-5 w-5" />
                تماس با ما
            </a>
            <a href="{{ route('faq') }}" class="btn-outline">
                <x-icon name="question" class="h-5 w-5" />
                پرسش‌های متداول
            </a>
        </div>
    </section>
</div>
@endsection
