{{--
    Digino — site footer.
    Newsletter strip + four link columns + brand column + legal bar.
    Included by every public layout (not by auth / dashboard / admin layouts).
--}}
<footer class="mt-10 bg-ink-900 text-ink-300">

    {{-- ───────────────────────── newsletter ───────────────────────── --}}
    <div class="border-b border-white/10">
        <div class="container">
            <div class="flex flex-col items-center gap-5 py-8 text-center lg:flex-row lg:justify-between lg:text-start">
                <div>
                    <h2 class="text-base font-extrabold text-white md:text-lg">از تخفیف‌ها و جدیدترین‌ها باخبر شوید!</h2>
                    <p class="mt-1.5 text-[0.8125rem] text-ink-400">
                        با عضویت در خبرنامه، از جدیدترین محصولات و پیشنهادهای ویژه مطلع شوید.
                    </p>
                </div>

                <form action="{{ route('ajax.newsletter') }}" method="POST" data-ajax-form data-reset
                      class="flex w-full max-w-md items-center gap-2">
                    @csrf
                    <div class="relative flex-1">
                        <input type="email" name="email" required
                               class="h-11 w-full rounded-field border border-white/15 bg-white/5 px-4 text-sm text-white placeholder:text-ink-500 transition-all focus:border-brand-400 focus:bg-white/10 focus:outline-none"
                               placeholder="ایمیل خود را وارد کنید...">
                        <p data-error-for="email" class="hidden pt-1 text-start text-2xs text-brand-400"></p>
                    </div>
                    <button type="submit" class="btn-primary h-11 shrink-0 px-6">
                        <span data-submit-text>عضویت</span>
                        <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ─────────────────────────── columns ─────────────────────────── --}}
    <div class="container">
        <div class="grid gap-8 py-10 sm:grid-cols-2 lg:grid-cols-5">

            {{-- brand column --}}
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                    <img src="{{ asset('images/logo/mark.svg') }}" alt="دیجی‌نو" width="40" height="40" class="h-10 w-10">
                    <span class="leading-none">
                        <span class="block text-xl font-extrabold text-brand-500">دیجی‌نو</span>
                        <span class="mt-1 block text-2xs text-ink-400">{{ digino('site_tagline', 'خرید هوشمند') }}</span>
                    </span>
                </a>

                <p class="mt-4 max-w-sm text-[0.8125rem] leading-7 text-ink-400">
                    دیجی‌نو، فروشگاه تخصصی محصولات دیجیتال با ضمانت اصالت کالا، بهترین قیمت و
                    ارسال سریع به سراسر کشور. هر آنچه برای یک خرید مطمئن لازم دارید، اینجاست.
                </p>

                <div class="mt-6 flex items-center gap-4">
                    <span class="grid h-16 w-14 place-items-center rounded-lg border border-white/10 bg-white/5 p-2 text-ink-400 transition-colors hover:text-white">
                        <x-icon name="shield-check" class="h-8 w-8" />
                    </span>
                    <div class="text-2xs leading-5 text-ink-500">
                        <p class="font-bold text-ink-300">نماد اعتماد الکترونیکی</p>
                        <p>ثبت‌شده در سامانه‌های رسمی کشور</p>
                    </div>
                </div>
            </div>

            {{-- about --}}
            <nav aria-labelledby="ft-about">
                <h3 id="ft-about" class="footer-title">درباره دیجی‌نو</h3>
                <ul>
                    <li><a href="{{ route('about') }}" class="footer-link">درباره ما</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">تماس با ما</a></li>
                    <li><a href="{{ route('contact') }}#careers" class="footer-link">همکاری با ما</a></li>
                    <li><a href="{{ route('privacy') }}" class="footer-link">حریم خصوصی</a></li>
                    <li><a href="{{ route('terms') }}" class="footer-link">شرایط استفاده</a></li>
                    <li><a href="{{ route('blog.index') }}" class="footer-link">مجله دیجی‌نو</a></li>
                </ul>
            </nav>

            {{-- buying guide --}}
            <nav aria-labelledby="ft-guide">
                <h3 id="ft-guide" class="footer-title">راهنمای خرید از دیجی‌نو</h3>
                <ul>
                    <li><a href="{{ route('faq') }}#order" class="footer-link">نحوه ثبت سفارش</a></li>
                    <li><a href="{{ route('faq') }}#payment" class="footer-link">روش‌های پرداخت</a></li>
                    <li><a href="{{ route('faq') }}#shipping" class="footer-link">ارسال و تحویل کالا</a></li>
                    <li><a href="{{ route('account.orders.index') }}" class="footer-link">پیگیری سفارش</a></li>
                    <li><a href="{{ route('faq') }}#size" class="footer-link">راهنمای سایز</a></li>
                    <li><a href="{{ route('shop.special') }}" class="footer-link">فروش ویژه</a></li>
                </ul>
            </nav>

            {{-- customer services --}}
            <nav aria-labelledby="ft-services">
                <h3 id="ft-services" class="footer-title">خدمات مشتریان</h3>
                <ul>
                    <li><a href="{{ route('faq') }}" class="footer-link">پرسش‌های متداول</a></li>
                    <li><a href="{{ route('faq') }}#returns" class="footer-link">رویه‌های بازگرداندن کالا</a></li>
                    <li><a href="{{ route('terms') }}" class="footer-link">شرایط استفاده</a></li>
                    <li><a href="{{ route('faq') }}#warranty" class="footer-link">گارانتی و خدمات</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">تماس با ما</a></li>
                    @foreach($footerPages ?? [] as $page)
                        <li><a href="{{ route('pages.show', $page->slug) }}" class="footer-link">{{ $page->title }}</a></li>
                    @endforeach
                </ul>
            </nav>

            {{-- contact --}}
            <div>
                <h3 class="footer-title">راه‌های ارتباطی</h3>
                <ul class="space-y-3 text-[0.8125rem]">
                    <li class="flex items-center gap-2">
                        <x-icon name="phone" class="h-4 w-4 shrink-0 text-ink-500" />
                        <a href="tel:{{ digino('support_phone', '02112345678') }}" class="ltr transition-colors hover:text-white">
                            {{ digino('support_phone', '021-12345678') }}
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-icon name="mail" class="h-4 w-4 shrink-0 text-ink-500" />
                        <a href="mailto:{{ digino('support_email', 'info@digino.com') }}" class="ltr transition-colors hover:text-white">
                            {{ digino('support_email', 'info@digino.com') }}
                        </a>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-icon name="map-pin" class="mt-1 h-4 w-4 shrink-0 text-ink-500" />
                        <span class="leading-6">{{ digino('address', 'تهران، خیابان انقلاب، پلاک ۱۲۳') }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-icon name="clock" class="h-4 w-4 shrink-0 text-ink-500" />
                        <span>{{ digino('working_hours', 'شنبه تا پنجشنبه، ۹ تا ۱۸') }}</span>
                    </li>
                </ul>

                <div class="mt-5 flex items-center gap-2.5">
                    <a href="{{ digino('instagram', '#') }}" class="social-btn" aria-label="اینستاگرام دیجی‌نو" rel="nofollow noopener">
                        <x-icon name="instagram" class="h-5 w-5" />
                    </a>
                    <a href="{{ digino('telegram', '#') }}" class="social-btn" aria-label="تلگرام دیجی‌نو" rel="nofollow noopener">
                        <x-icon name="telegram" class="h-5 w-5" />
                    </a>
                    <a href="{{ digino('linkedin', '#') }}" class="social-btn" aria-label="لینکدین دیجی‌نو" rel="nofollow noopener">
                        <x-icon name="linkedin" class="h-5 w-5" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ───────────────────────── legal bar ───────────────────────── --}}
    <div class="border-t border-white/10">
        <div class="container">
            <div class="flex flex-col items-center justify-between gap-3 py-5 text-center text-2xs leading-6 text-ink-500 md:flex-row md:text-start">
                <p>
                    کلیه حقوق مادی و معنوی این وب‌سایت متعلق به <span class="font-bold text-ink-300">دیجی‌نو</span> است.
                    استفاده، بازنشر یا کپی‌برداری از طراحی و محتوای آن بدون اجازهٔ کتبی مجاز نیست.
                    <a href="{{ route('about') }}#license" class="text-brand-400 transition-colors hover:text-brand-300">مطالعهٔ کامل شرایط</a>
                </p>
                <p class="whitespace-nowrap">
                    طراحی و توسعه با دقت توسط
                    <span class="font-bold text-white">یارمحمدی</span>
                </p>
            </div>
        </div>
    </div>
</footer>
