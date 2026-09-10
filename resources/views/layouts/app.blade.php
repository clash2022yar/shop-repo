<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','دیجینو — خرید هوشمند | Digino')</title>
    <meta name="description" content="دیجینو فروشگاه تخصصی محصولات دیجیتال با ضمانت اصالت و بهترین قیمت">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { vazir: ['Vazirmatn','Tahoma', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#ef4056', hover:'#d32f44', light:'#fff1f2', dark:'#be123c' },
                        digi: '#ef394e',
                        dark: '#232933',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-soft': 'pulseSoft 2s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        *{font-family: 'Vazirmatn', Tahoma, sans-serif}
        body{background:#f5f5f7; overflow-x:hidden}
        ::-webkit-scrollbar{width:8px;height:8px} ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:999px}
        @keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        @keyframes slideUp{from{transform:translateY(16px);opacity:0}to{transform:translateY(0);opacity:1}}
        @keyframes pulseSoft{0%,100%{transform:scale(1)}50%{transform:scale(1.03)}}
        .shimmer{position:relative;overflow:hidden}
        .shimmer::after{content:'';position:absolute;inset:0;transform:translateX(-100%);background:linear-gradient(90deg,transparent,rgba(255,255,255,.6),transparent);animation:shimmer 1.2s infinite}
        @keyframes shimmer{100%{transform:translateX(100%)}}
        .no-scrollbar::-webkit-scrollbar{display:none}
        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
    </style>
    @stack('head')
</head>
<body class="font-vazir text-[#3f4064] antialiased">
    @include('includes.header')
    <main class="min-h-screen">
        @yield('content')
    </main>
    @if(!isset($hideFooter) || !$hideFooter)
        @include('includes.footer')
    @endif

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] flex flex-col gap-2 pointer-events-none"></div>

    <!-- Quick View Modal -->
    <div id="quickViewModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-auto p-6 relative scale-95 transition-transform duration-300" id="quickViewContent">
            <button onclick="closeModal()" class="absolute left-4 top-4 w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
            <div id="quickViewBody" class="pt-4"></div>
        </div>
    </div>

    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
