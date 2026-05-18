@extends('layouts.admin')

@section('content')
@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<!-- Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[var(--color-eco-green)]">
        <h3 class="text-gray-500 text-xs uppercase font-bold tracking-wider">Total Users</h3>
        <p class="text-3xl font-black mt-2 font-serif text-gray-800">{{ $totalUsers }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
        <h3 class="text-gray-500 text-xs uppercase font-bold tracking-wider">Pending Users</h3>
        <p class="text-3xl font-black mt-2 font-serif text-gray-800">{{ count($pendingUsers) }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-xs uppercase font-bold tracking-wider">Active Campaigns</h3>
        <p class="text-3xl font-black mt-2 font-serif text-gray-800">{{ $activeCampaigns }} <span class="text-xs text-gray-400 font-normal">/ {{ $totalCampaigns }}</span></p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
        <h3 class="text-gray-500 text-xs uppercase font-bold tracking-wider">Active Resources</h3>
        <p class="text-3xl font-black mt-2 font-serif text-gray-800">{{ $activeSolutions }} <span class="text-xs text-gray-400 font-normal">/ {{ $totalSolutions }}</span></p>
    </div>
</div>

<!-- Pending Registrations Queue -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="font-bold text-lg font-serif text-gray-800">Pending User Registrations (Onboarding Verification)</h2>
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">{{ count($pendingUsers) }} Pending</span>
    </div>
    <div class="overflow-x-auto">
        @if(count($pendingUsers) > 0)
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Org Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Proof Doc</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($pendingUsers as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->role }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->organization_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->contact_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->registration_certificate)
                                    <a href="{{ asset('storage/' . $user->registration_certificate) }}" target="_blank" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-900 border border-blue-200 px-2 py-1 rounded bg-blue-50">
                                        📄 View Certificate
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">None Provided</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 font-semibold text-xs border border-green-200 px-2.5 py-1 rounded bg-green-50 hover:bg-green-100 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline-flex items-center gap-1.5">
                                        @csrf
                                        <input type="text" name="notes" placeholder="Rejection notes" required class="px-2 py-1 text-xs border rounded w-36 focus:outline-none focus:ring-1 focus:ring-red-500">
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs border border-red-200 px-2.5 py-1 rounded bg-red-50 hover:bg-red-100 transition">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500 text-sm">No pending user validations at this time.</p>
            </div>
        @endif
    </div>
</div>

<!-- Pending Campaigns Queue -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="font-bold text-lg font-serif text-gray-800">Pending Campaign Moderation</h2>
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">{{ count($pendingCampaigns) }} Pending Review</span>
    </div>
    <div class="overflow-x-auto">
        @if(count($pendingCampaigns) > 0)
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Proposer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Goal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($pendingCampaigns as $campaign)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $campaign->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $campaign->category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $campaign->proposer->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-serif font-bold">${{ number_format($campaign->goal, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $campaign->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.campaigns.approve', $campaign) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 font-semibold text-xs border border-green-200 px-2.5 py-1 rounded bg-green-50 hover:bg-green-100 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.campaigns.reject', $campaign) }}" method="POST" class="inline-flex items-center gap-1.5">
                                        @csrf
                                        <input type="text" name="reject_reason" placeholder="Reason" required class="px-2 py-1 text-xs border rounded w-36 focus:outline-none focus:ring-1 focus:ring-red-500">
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs border border-red-200 px-2.5 py-1 rounded bg-red-50 hover:bg-red-100 transition">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500 text-sm">No campaigns waiting for moderation.</p>
            </div>
        @endif
    </div>
</div>

<!-- Pending Solutions/Resources Queue -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="font-bold text-lg font-serif text-gray-800">Pending Resource & Recycling Point Moderation</h2>
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">{{ count($pendingSolutions) }} Pending Review</span>
    </div>
    <div class="overflow-x-auto">
        @if(count($pendingSolutions) > 0)
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Resource Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Proposer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($pendingSolutions as $solution)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $solution->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($solution->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $solution->proposer->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $solution->address }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $solution->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.solutions.approve', $solution) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 font-semibold text-xs border border-green-200 px-2.5 py-1 rounded bg-green-50 hover:bg-green-100 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.solutions.reject', $solution) }}" method="POST" class="inline-flex items-center gap-1.5">
                                        @csrf
                                        <input type="text" name="reject_reason" placeholder="Reason" required class="px-2 py-1 text-xs border rounded w-36 focus:outline-none focus:ring-1 focus:ring-red-500">
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs border border-red-200 px-2.5 py-1 rounded bg-red-50 hover:bg-red-100 transition">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500 text-sm">No resources waiting for moderation.</p>
            </div>
        @endif
    </div>
</div>
@endsection

