<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - {{ config('app.name', 'EcoNexus') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased flex h-screen overflow-hidden">
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        <main>
            <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
                <div class="mb-8 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900 font-serif">Admin Dashboard</h1>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.export') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold transition shadow-sm">
                            📊 Export CSV Report
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Logout</button>
                        </form>
                    </div>
                </div>
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
