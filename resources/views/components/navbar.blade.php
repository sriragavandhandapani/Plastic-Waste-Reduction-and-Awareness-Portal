<nav class="bg-[var(--color-eco-green)] text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center font-serif text-2xl font-bold text-[var(--color-eco-accent)]">
                    EcoNexus
                </a>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="{{ route('explore.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Explore</a>
                </div>
            </div>
            <div class="flex items-center">
                @auth
                    @if(Auth::user()->role === 'Admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    @elseif(in_array(Auth::user()->role, ['Proposer', 'Recycling Agency', 'Awareness Organization']))
                        <a href="{{ route('proposer.dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    @endif

                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log in</a>
                    <a href="{{ route('register') }}" class="bg-[var(--color-eco-accent)] text-[var(--color-eco-green)] hover:bg-opacity-90 px-4 py-2 rounded-md text-sm font-bold ml-3 transition">Sign up</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
