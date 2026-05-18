@extends('layouts.app')

@section('title', 'Campaign Details')

@section('content')
<div class="bg-[var(--color-eco-bg)] min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('explore.index') }}" class="text-[var(--color-eco-green)] hover:underline mb-6 inline-block">&larr; Back to Explore</a>
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <img src="https://images.unsplash.com/photo-1596558450255-7c0b7be9d56a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Campaign Banner" class="w-full h-64 object-cover">
            
            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-3xl font-bold text-gray-900 font-serif">Biodegradable Bubble Wrap</h1>
                    <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-semibold">Packaging</span>
                </div>
                
                <p class="text-gray-500 mb-6 border-b pb-6">Proposed by <span class="font-bold text-[var(--color-eco-green)]">GreenInnovate</span> on May 10, 2026</p>
                
                <h2 class="text-xl font-bold mb-3 font-serif">About the Project</h2>
                <p class="text-gray-700 mb-6 leading-relaxed">
                    Our project aims to replace traditional plastic bubble wrap with a completely biodegradable alternative made from seaweed extract. This new material offers the same protective qualities for shipping but dissolves harmlessly in water or composts within weeks.
                </p>
                
                <h2 class="text-xl font-bold mb-3 font-serif">Impact Potential</h2>
                <ul class="list-disc list-inside text-gray-700 mb-8 space-y-2">
                    <li>Reduces microplastic pollution in oceans</li>
                    <li>Lowers carbon footprint of packaging manufacturing</li>
                    <li>Creates secondary market for sustainable seaweed farming</li>
                </ul>

                <div class="bg-[var(--color-eco-bg)] p-6 rounded-lg border border-green-200 text-center">
                    <h3 class="text-lg font-bold text-[var(--color-eco-green)] mb-2">Interested in supporting this idea?</h3>
                    <p class="text-sm text-gray-600 mb-4">Register as a Recycling Agency or Awareness Organization to connect with the proposer.</p>
                    <a href="{{ route('register') }}" class="inline-block bg-[var(--color-eco-green)] text-white px-6 py-2 rounded font-bold hover:bg-opacity-90 transition">Join EcoNexus to Connect</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
