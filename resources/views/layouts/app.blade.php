<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EcoNexus') }} - @yield('title', 'Plastic Waste Reduction')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[var(--color-eco-bg)] text-[var(--color-eco-slate)] font-sans antialiased flex flex-col min-h-screen">
    <x-navbar />
    
    <main class="flex-grow">
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
