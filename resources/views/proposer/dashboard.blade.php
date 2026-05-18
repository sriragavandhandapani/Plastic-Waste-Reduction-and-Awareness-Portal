@extends('layouts.proposer')

@section('content')
@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Form Submission Failed!</strong>
        <span class="block sm:inline">Please correct the highlighted validation errors inside the form.</span>
    </div>
@endif

@php
    $notifications = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->get();
@endphp
@if(count($notifications) > 0)
    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl shadow-sm" x-data="{ showNotifications: true }" x-show="showNotifications">
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-bold text-sm flex items-center gap-1.5">🔔 Notification Center <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-800">{{ count($notifications) }} New</span></h4>
            <button @click="showNotifications = false" class="text-xs text-blue-500 hover:text-blue-700 font-semibold">Dismiss</button>
        </div>
        <ul class="space-y-2.5">
            @foreach($notifications as $notif)
                <li class="flex justify-between items-start text-xs border-b border-blue-100 pb-2 last:border-0 last:pb-0">
                    <span class="leading-relaxed">{{ $notif->message }}</span>
                    <form action="{{ route('notifications.read', $notif) }}" method="POST" class="ml-4 shrink-0">
                        @csrf
                        <button type="submit" class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded hover:bg-blue-700 font-bold transition">Mark Read</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div x-data="{ 
    showCampaignModal: {{ $errors->hasAny(['title', 'category', 'goal', 'start_date', 'end_date', 'description']) ? 'true' : 'false' }}, 
    showSolutionModal: {{ $errors->hasAny(['name', 'type', 'address', 'latitude', 'longitude', 'description']) ? 'true' : 'false' }} 
}">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-[var(--color-eco-green)]">Welcome, {{ Auth::user()->name }}!</h2>
            <p class="text-gray-600 text-sm">{{ Auth::user()->role }} Dashboard</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-4">
            <button @click="showSolutionModal = true" class="bg-white border-2 border-[var(--color-eco-green)] text-[var(--color-eco-green)] px-4 py-2 rounded font-bold hover:bg-green-50 transition">
                + New Resource/Location
            </button>
            <button @click="showCampaignModal = true" class="bg-[var(--color-eco-accent)] text-[var(--color-eco-green)] px-4 py-2 rounded font-bold hover:bg-opacity-90 transition">
                + New Campaign
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="font-bold text-lg font-serif">Your Campaigns</h3>
            </div>
            <div class="p-0">
                @if(count($campaigns) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($campaigns as $campaign)
                            <li class="p-6">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900">{{ $campaign->title }}</h4>
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-800 border-green-200',
                                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200'
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] border font-semibold rounded-full {{ $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $campaign->category }} | Goal: ${{ number_format($campaign->goal, 2) }}</p>
                                @if($campaign->status === 'rejected')
                                    <p class="text-xs text-red-600 mt-1.5 font-bold">Reason: {{ $campaign->reject_reason ?? 'No reason provided.' }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-6 text-gray-500 italic">You haven't submitted any campaigns yet.</div>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="font-bold text-lg font-serif">Your Resources & Solutions</h3>
            </div>
            <div class="p-0">
                @if(count($solutions) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($solutions as $solution)
                            <li class="p-6">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900">{{ $solution->name }}</h4>
                                    <span class="px-2 py-0.5 text-[10px] border font-semibold rounded-full {{ $statusColors[$solution->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst($solution->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ ucfirst($solution->type) }} | {{ $solution->location['address'] ?? 'No Address' }}</p>
                                @if($solution->status === 'rejected')
                                    <p class="text-xs text-red-600 mt-1.5 font-bold">Reason: {{ $solution->reject_reason ?? 'No reason provided.' }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-6 text-gray-500 italic">You haven't added any resources yet.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Campaign Modal -->
    <div x-show="showCampaignModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCampaignModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCampaignModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showCampaignModal" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('proposer.campaigns.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Create New Campaign</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 p-2 border @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Category</label>
                                <input type="text" name="category" value="{{ old('category') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('category') border-red-500 @enderror">
                                @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Goal Amount / Target</label>
                                <input type="number" step="0.01" name="goal" value="{{ old('goal') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('goal') border-red-500 @enderror">
                                @error('goal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Description</label>
                                <textarea name="description" rows="3" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--color-eco-green)] text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" @click="showCampaignModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Solution Modal -->
    <div x-show="showSolutionModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showSolutionModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showSolutionModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showSolutionModal" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('proposer.solutions.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Add Resource or Recycling Point</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Type</label>
                                <select name="type" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('type') border-red-500 @enderror">
                                    <option value="recycling point" {{ old('type') == 'recycling point' ? 'selected' : '' }}>Recycling Point</option>
                                    <option value="education program" {{ old('type') == 'education program' ? 'selected' : '' }}>Education Program</option>
                                    <option value="eco-friendly product" {{ old('type') == 'eco-friendly product' ? 'selected' : '' }}>Eco-friendly Product</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Address</label>
                                <input type="text" name="address" value="{{ old('address') }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium">Latitude</label>
                                    <input type="text" name="latitude" value="{{ old('latitude') }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('latitude') border-red-500 @enderror">
                                    @error('latitude')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium">Longitude</label>
                                    <input type="text" name="longitude" value="{{ old('longitude') }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('longitude') border-red-500 @enderror">
                                    @error('longitude')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Description</label>
                                <textarea name="description" rows="3" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--color-eco-green)] text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" @click="showSolutionModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
