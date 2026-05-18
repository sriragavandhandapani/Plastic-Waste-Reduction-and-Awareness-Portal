@extends('layouts.app')

@section('title', $solution->name . ' - Resource Details')

@section('content')
<div class="bg-[var(--color-eco-bg)] min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('explore.index') }}" class="text-[var(--color-eco-green)] hover:underline mb-6 inline-block font-semibold">&larr; Back to Explore</a>
        
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm font-semibold">
                🎉 {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100">
            <img src="https://images.unsplash.com/photo-1605600659908-0ef719419d41?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Resource Banner" class="w-full h-72 object-cover">
            
            <div class="p-8">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                    <h1 class="text-3xl font-black text-gray-900 font-serif leading-tight">{{ $solution->name }}</h1>
                    <span class="inline-block py-1 px-3 rounded-full bg-green-50 border border-green-200 text-[var(--color-eco-green)] text-xs font-bold uppercase">{{ ucfirst($solution->type) }}</span>
                </div>
                
                <p class="text-gray-500 mb-6 border-b border-gray-100 pb-6 text-sm">
                    Submitted by <span class="font-bold text-[var(--color-eco-green)]">{{ $solution->proposer->name ?? 'EcoNexus Partner' }}</span>
                    @if(isset($solution->proposer->organization_name))
                        ({{ $solution->proposer->organization_name }})
                    @endif
                    on {{ $solution->created_at->format('M d, Y') }}
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h2 class="text-lg font-bold mb-3 font-serif text-gray-800">Resource Description</h2>
                        <p class="text-gray-600 leading-relaxed text-sm whitespace-pre-line">{{ $solution->description }}</p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Location details</h3>
                            
                            <div class="text-xs text-gray-600 space-y-3">
                                <div>
                                    <strong class="text-gray-500 uppercase tracking-wide block mb-0.5">Physical Address</strong>
                                    <p class="text-gray-800 font-semibold">{{ $solution->location['address'] ?? 'No address registered' }}</p>
                                </div>
                                
                                @if(isset($solution->location['lat']) && isset($solution->location['lng']) && ($solution->location['lat'] != 0 || $solution->location['lng'] != 0))
                                    <div>
                                        <strong class="text-gray-500 uppercase tracking-wide block mb-0.5">Coordinates</strong>
                                        <p class="font-mono text-gray-700">Latitude: {{ $solution->location['lat'] }} | Longitude: {{ $solution->location['lng'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(Auth::check())
                            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                                <p class="text-xs text-gray-500 font-semibold mb-2">Connect with the partner for business or drop-offs</p>
                                <a href="mailto:{{ $solution->proposer->email ?? '' }}" class="inline-block bg-[var(--color-eco-green)] text-white text-xs px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-sm w-full">
                                    ✉ Contact {{ $solution->proposer->name ?? 'Partner' }}
                                </a>
                            </div>
                        @else
                            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                                <a href="{{ route('register') }}" class="inline-block bg-[var(--color-eco-green)] text-white text-xs px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-sm w-full">
                                    Join EcoNexus to Connect
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback & Review engine -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-bold font-serif text-gray-800 mb-6 flex items-center gap-2">
                ⭐ Community Ratings & Reviews
            </h2>

            @php
                $reviews = $solution->reviews ?? [];
                $reviewsCount = count($reviews);
                $averageRating = $solution->rating ?? 0;
            @endphp

            <div class="flex flex-col md:flex-row items-center gap-8 mb-8 border-b border-gray-100 pb-8">
                <div class="text-center md:border-r md:pr-8 md:border-gray-100">
                    <p class="text-5xl font-black text-gray-800 font-serif leading-none">{{ number_format($averageRating, 1) }}</p>
                    <div class="flex justify-center text-amber-400 my-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-300 stroke-current' }}" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">{{ $reviewsCount }} {{ Str::plural('rating', $reviewsCount) }}</p>
                </div>

                <div class="flex-1 w-full">
                    <h3 class="text-sm font-bold text-gray-700 mb-4">Rate & Review this Resource</h3>
                    @if(Auth::check())
                        <form action="{{ route('explore.solutions.reviews.store', $solution) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Your Rating</label>
                                <div class="flex gap-2" x-data="{ rating: 5 }">
                                    <input type="hidden" name="rating" :value="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" @click="rating = {{ $i }}" class="focus:outline-none transition transform hover:scale-110">
                                            <svg class="w-7 h-7" :class="rating >= {{ $i }} ? 'text-amber-400 fill-current' : 'text-gray-300 stroke-current'" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label for="comment" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Your Comments</label>
                                <textarea id="comment" name="comment" rows="3" required placeholder="Write a review about your experience with this resource..." class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[var(--color-eco-green)] focus:ring-1 focus:ring-[var(--color-eco-green)] transition"></textarea>
                            </div>

                            <button type="submit" class="bg-[var(--color-eco-green)] text-white text-xs px-6 py-2.5 rounded-lg font-bold hover:bg-opacity-90 transition shadow-sm">
                                Submit Review
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center">
                            <p class="text-xs text-gray-600 mb-2">You must be logged in to leave reviews and ratings.</p>
                            <a href="{{ route('login') }}" class="text-xs text-[var(--color-eco-green)] font-bold hover:underline">Log in to your account &rarr;</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review list -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Review Feed</h3>
                @if($reviewsCount > 0)
                    <ul class="divide-y divide-gray-100">
                        @foreach($reviews as $rev)
                            <li class="py-4 first:pt-0 last:pb-0">
                                <div class="flex justify-between items-start mb-1">
                                    <div>
                                        <h4 class="font-bold text-xs text-gray-800">{{ $rev['user_name'] ?? 'EcoNexus Community' }}</h4>
                                        <div class="flex text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i <= $rev['rating'] ? 'fill-current' : 'text-gray-200 stroke-current' }}" viewBox="0 0 24 24">
                                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-semibold">{{ \Carbon\Carbon::parse($rev['created_at'])->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-600 leading-relaxed">{{ $rev['comment'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-xs text-gray-500 italic">No community reviews have been left for this resource yet. Be the first to share your rating!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
