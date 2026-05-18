@extends('layouts.app')

@section('title', 'Explore Ideas')

@section('content')
<div class="bg-[var(--color-eco-bg)] min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl font-serif">Explore Solutions</h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Discover innovative ideas for reducing plastic waste.
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-white p-4 rounded-lg shadow">
            <form action="{{ route('explore.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="w-full md:w-1/2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search ideas..." class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:border-[var(--color-eco-green)]">
                </div>
                <div class="w-full md:w-auto flex gap-4">
                    <select name="category" class="w-full md:w-auto px-4 py-2 rounded border border-gray-300 focus:outline-none focus:border-[var(--color-eco-green)]" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="packaging" {{ ($category ?? '') == 'packaging' ? 'selected' : '' }}>Alternative Packaging</option>
                        <option value="recycling" {{ ($category ?? '') == 'recycling' ? 'selected' : '' }}>Recycling Tech</option>
                        <option value="awareness" {{ ($category ?? '') == 'awareness' ? 'selected' : '' }}>Awareness Campaigns</option>
                        <option value="recycling point" {{ ($category ?? '') == 'recycling point' ? 'selected' : '' }}>Recycling Points</option>
                        <option value="education program" {{ ($category ?? '') == 'education program' ? 'selected' : '' }}>Education Programs</option>
                        <option value="eco-friendly product" {{ ($category ?? '') == 'eco-friendly product' ? 'selected' : '' }}>Eco-friendly Products</option>
                    </select>
                    <a href="{{ route('explore.map') }}" class="bg-[var(--color-eco-green)] text-white px-4 py-2 rounded hover:bg-green-700 whitespace-nowrap">View Map</a>
                    <button type="submit" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Search</button>
                </div>
            </form>
        </div>

        <h2 class="text-2xl font-bold mb-4 font-serif text-gray-800">Campaigns</h2>
        @if(count($campaigns) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($campaigns as $campaign)
                    <x-card title="{{ $campaign->title }}" image="{{ $campaign->banner_image ?? 'https://images.unsplash.com/photo-1596558450255-7c0b7be9d56a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}">
                        <p class="mb-4 text-sm text-gray-600">{{ Str::limit($campaign->description, 100) }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xs font-semibold inline-block py-1 px-2 rounded text-[var(--color-eco-green)] bg-green-200 uppercase">{{ $campaign->category }}</span>
                            <span class="text-sm text-gray-500">By {{ $campaign->proposer->name ?? 'Unknown' }}</span>
                        </div>
                        <x-slot name="footer">
                            <a href="{{ route('explore.campaigns.show', $campaign) }}" class="text-[var(--color-eco-green)] font-bold hover:underline">View Details &rarr;</a>
                        </x-slot>
                    </x-card>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mb-12">No campaigns found.</p>
        @endif

        <h2 class="text-2xl font-bold mb-4 font-serif text-gray-800">Solutions & Resources</h2>
        @if(count($solutions) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($solutions as $solution)
                    <x-card title="{{ $solution->name }}" image="https://images.unsplash.com/photo-1605600659908-0ef719419d41?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80">
                        <p class="mb-4 text-sm text-gray-600">{{ Str::limit($solution->description, 100) }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xs font-semibold inline-block py-1 px-2 rounded text-[var(--color-eco-green)] bg-green-200 uppercase">{{ $solution->type }}</span>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            {{ $solution->location['address'] ?? '' }}
                        </div>
                        <x-slot name="footer">
                            <a href="{{ route('explore.solutions.show', $solution) }}" class="text-[var(--color-eco-green)] font-bold hover:underline">View Details &rarr;</a>
                        </x-slot>
                    </x-card>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No solutions found.</p>
        @endif
    </div>
</div>
@endsection
