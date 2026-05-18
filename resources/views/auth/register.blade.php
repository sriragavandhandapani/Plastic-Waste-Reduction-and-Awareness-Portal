<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - {{ config('app.name', 'EcoNexus') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[var(--color-eco-bg)] font-sans antialiased min-h-screen flex items-center justify-center py-10">

<div class="w-full max-w-lg px-6 py-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="text-3xl font-extrabold font-serif text-[var(--color-eco-green)]">EcoNexus</a>
        <p class="mt-2 text-gray-500 text-sm">Create your account and join the movement</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8" x-data="{ role: '{{ old('role', 'End User') }}' }">

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">I am a...</label>
                <select id="role" name="role" x-model="role" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition bg-white">
                    <option value="End User">End User</option>
                    <option value="Proposer">Proposer (Individual)</option>
                    <option value="Recycling Agency">Recycling Agency</option>
                    <option value="Awareness Organization">Awareness Organization</option>
                </select>
            </div>

            <!-- Organization name (only for orgs) -->
            <div x-show="role === 'Recycling Agency' || role === 'Awareness Organization'" x-transition>
                <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                <input id="organization_name" type="text" name="organization_name" value="{{ old('organization_name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
                <p class="mt-1 text-xs text-amber-600">⚠ Organizations require admin approval before accessing the platform.</p>
            </div>

            <!-- Contact Number (for proposers and organizations) -->
            <div x-show="role !== 'End User'" x-transition>
                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
            </div>

            <!-- Registration Certificate / Proof Document (for proposers and organizations) -->
            <div x-show="role !== 'End User'" x-transition>
                <label for="registration_certificate" class="block text-sm font-medium text-gray-700 mb-1">Registration Certificate / Proof Document</label>
                <input id="registration_certificate" type="file" name="registration_certificate" accept=".pdf,.doc,.docx,image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[var(--color-eco-green)] hover:file:bg-green-100">
                <p class="mt-1 text-xs text-gray-400">Please upload your business registration certificate, NGO certificate, or identity proof (PDF, Word, or Image, max 5MB).</p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-eco-green)] transition">
            </div>

            <!-- Profile Photo -->
            <div>
                <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-1">Profile Photo <span class="text-gray-400">(optional)</span></label>
                <input id="profile_photo" type="file" name="profile_photo" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[var(--color-eco-green)] hover:file:bg-green-100">
            </div>

            <button type="submit"
                class="w-full bg-[var(--color-eco-green)] text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-eco-green)]">
                Create Account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[var(--color-eco-green)] font-semibold hover:underline">Sign in here</a>
        </p>
    </div>
</div>

</body>
</html>
