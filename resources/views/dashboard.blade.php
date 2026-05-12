@extends('layouts.master')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Stats Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @php
        $stats = [
            [
                'title' => 'Total Team Members',
                'value' => \App\Models\User::whereHas('role', fn($q) => $q->where('code', 'team_member'))->count(),
                'icon' => 'fa-users',
                'bg' => 'bg-blue-500'
            ],
            [
                'title' => 'Total Units',
                'value' => \App\Models\Unit::count(),
                'icon' => 'fa-building',
                'bg' => 'bg-purple-500'
            ],
            [
                'title' => 'Total Events',
                'value' => \App\Models\Event::count(),
                'icon' => 'fa-calendar-check',
                'bg' => 'bg-green-500'
            ],
            [
                'title' => 'New Applications',
                'value' => \App\Models\TeamApplication::where('status', 'pending')->count(),
                'icon' => 'fa-user-plus',
                'bg' => 'bg-orange-500'
            ]
        ];
    @endphp
    
    @foreach($stats as $stat)
    <div class="bg-white rounded-lg p-6 flex items-center justify-between shadow-sm">
        <div>
            <p class="text-sm text-gray-500 font-medium">{{ $stat['title'] }}</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stat['value'] }}</p>
        </div>
        <div class="w-12 h-12 rounded-full {{ $stat['bg'] }} flex items-center justify-center shadow-md">
            <i class="fas {{ $stat['icon'] }} text-white text-lg"></i>
        </div>
    </div>
    @endforeach
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Users Table -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-800">Recent Team Members</h3>
            <a href="{{ route('users.index') }}" class="text-sm text-blue-500 hover:text-blue-700 font-medium">View All</a>
        </div>
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-gray-100">
                    <th class="text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Name</th>
                    <th class="text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Unit</th>
                    <th class="text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Designation</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @php
                    $recentUsers = \App\Models\User::whereHas('role', fn($q) => $q->where('code', 'team_member'))
                        ->with('unit')
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                
                @forelse($recentUsers as $user)
                <tr class="border-b border-gray-50 last:border-0">
                    <td class="py-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-semibold mr-3">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3">{{ $user->unit?->name ?? '-' }}</td>
                    <td class="py-3">{{ $user->designation ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-6 text-center text-gray-500">No team members found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quick Actions & Status -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-plus text-blue-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-700">Add Team Member</span>
                </a>
                <a href="{{ route('events.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-plus text-green-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-700">Create Event</span>
                </a>
                <a href="{{ route('team-applications.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-clipboard-list text-purple-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-700">Review Applications</span>
                </a>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">System Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Database</span>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Connected</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Security</span>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Active</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last Backup</span>
                    <span class="text-xs text-gray-500">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
