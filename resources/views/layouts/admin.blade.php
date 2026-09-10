<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','پنل ادمین — دیجینو')</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{vazir:['Vazirmatn','sans-serif']},colors:{primary:'#ef4056'}}}}</script>
    <style>*{font-family:'Vazirmatn',sans-serif} body{background:#f8fafc} ::-webkit-scrollbar{width:8px} ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:999px}</style>
</head>
<body class="font-vazir bg-[#f8fafc] text-[#1e293b]">
    <div class="flex min-h-screen">
        @include('includes.sidebar-admin')
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar -->
            <header class="h-[64px] bg-white border-b flex items-center justify-between px-6 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <h1 class="font-bold">@yield('page_title','داشبورد')</h1>
                    <span class="hidden md:inline text-xs text-gray-500">خوش آمدید، یارمحمدی</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative hidden md:block">
                        <input placeholder="جستجو..." class="h-9 w-64 bg-gray-50 border rounded-lg pr-9 pl-3 text-sm outline-none focus:bg-white focus:border-gray-300">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <button class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center relative">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 8a6 6 0 0 1 12 0c0 7-6 5-6 11H6s-2-4 0-11z"/><path d="M10 21h4"/><circle cx="12" cy="8" r="1" fill="currentColor"/></svg>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-[#ef4056] text-white text-[10px] rounded-full flex items-center justify-center">3</span>
                    </button>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs">ی</div>
                </div>
            </header>
            <main class="p-6 flex-1 overflow-auto">
                @if(session('success'))
                <div class="mb-4 bg-[#f0fdf4] border border-[#bbf7d0] text-[#15803d] rounded-lg p-3 text-sm">{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
            <footer class="p-4 text-center text-xs text-gray-400 border-t bg-white">پنل مدیریت دیجینو — سازنده: یارمحمدی — PHP 8.3 | Laravel 13</footer>
        </div>
    </div>
    <div id="toast-container" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] flex flex-col gap-2"></div>
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
