<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'EcoNexus') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[var(--color-eco-bg)] font-sans antialiased min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6 py-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="text-3xl font-extrabold font-serif text-[var(--color-eco-green)]">EcoNexus</a>
        <p class="mt-2 text-gray-500 text-sm">Sign in to your account</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8">

        @if (session('status'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 mr-2">
                    Remember me
                </label>
            </div>

            <button type="submit"
                class="w-full bg-[var(--color-eco-green)] text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-eco-green)]">
                Sign In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[var(--color-eco-green)] font-semibold hover:underline">Register here</a>
        </p>
    </div>
</div>

</body>
</html>
