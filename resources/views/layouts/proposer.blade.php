<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'EcoNexus') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased flex h-screen overflow-hidden">
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        <main>
            <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
                <div class="mb-8 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900 font-serif">Dashboard</h1>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm bg-red-600 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                </div>
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
