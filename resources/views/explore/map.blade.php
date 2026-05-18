@extends('layouts.app')

@section('title', 'Recycling Point Locator')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<div class="bg-[var(--color-eco-bg)] min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl font-serif">Recycling Point Locator</h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Find verified recycling drop-off locations near you.
            </p>
        </div>

        <div class="mb-4">
            <a href="{{ route('explore.index') }}" class="text-[var(--color-eco-green)] hover:underline">&larr; Back to Explore</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2 border-[var(--color-eco-green)]">
            <div id="map" style="height: 600px; width: 100%;"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default center (can be based on user location, for now a generic center)
        var map = L.map('map').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var points = @json($recyclingPoints);
        
        var bounds = [];

        points.forEach(function(point) {
            if (point.location && point.location.lat && point.location.lng) {
                var marker = L.marker([point.location.lat, point.location.lng]).addTo(map);
                marker.bindPopup(`
                    <div class="font-sans">
                        <strong class="text-lg">${point.name}</strong><br>
                        <span class="text-sm text-gray-600">${point.location.address}</span><br>
                        <p class="mt-2 text-sm">${point.description || ''}</p>
                        <p class="mt-1 text-xs text-green-600">Added by Proposer: ${point.proposer_id || 'System'}</p>
                    </div>
                `);
                bounds.push([point.location.lat, point.location.lng]);
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds);
        } else {
            // Set default view to India if no points, as example
            map.setView([20.5937, 78.9629], 5);
        }
    });
</script>
@endsection
