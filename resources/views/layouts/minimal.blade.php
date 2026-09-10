<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','دیجینو')</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{vazir:['Vazirmatn','sans-serif']},colors:{primary:'#ef4056'}}}}</script>
    <style>*{font-family:'Vazirmatn',sans-serif} body{background:#fff}</style>
</head>
<body class="font-vazir bg-white">
    @include('includes.header-minimal')
    <main>@yield('content')</main>
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
