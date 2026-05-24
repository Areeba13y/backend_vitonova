@extends('layouts.master')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
@php
    $totalMembers = \App\Models\User::whereHas('role', fn($q) => $q->where('code', 'team_member'))->count();
    $totalUnits = \App\Models\Unit::count();
    $totalEvents = \App\Models\Event::count();
    $pendingApplications = \App\Models\TeamApplication::where('status', 'pending')->count();
    $totalApplications = \App\Models\TeamApplication::count();
    $totalContacts = \App\Models\ContactMessage::count();
    $totalCollaborations = \App\Models\Collaboration::count();
    
    $recentUsers = \App\Models\User::whereHas('role', fn($q) => $q->where('code', 'team_member'))
        ->with('unit')
        ->latest()
        ->take(5)
        ->get();
    
    $eventsByMonth = \App\Models\Event::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();
    $monthLabels = [];
    $monthData = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthLabels[] = date('M', mktime(0, 0, 0, $i, 1));
        $monthData[] = $eventsByMonth[$i] ?? 0;
    }
    
    $applicationStatuses = \App\Models\TeamApplication::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();
@endphp

<!-- Stats Cards Row -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 flex items-center">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
            <i class="fas fa-users text-gray-600 text-lg"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm text-gray-500 truncate">Team Members</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalMembers }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 flex items-center">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
            <i class="fas fa-building text-gray-600 text-lg"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm text-gray-500 truncate">Units</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalUnits }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 flex items-center">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
            <i class="fas fa-calendar text-gray-600 text-lg"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm text-gray-500 truncate">Events</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalEvents }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 flex items-center">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
            <i class="fas fa-inbox text-gray-600 text-lg"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm text-gray-500 truncate">Pending Applications</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $pendingApplications }}</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
    <!-- Events by Month -->
    <div class="xl:col-span-2 bg-white rounded-lg shadow-sm p-4 sm:p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Events This Year</h3>
        <div class="h-56 sm:h-64 lg:h-72">
            <canvas id="eventsChart"></canvas>
        </div>
    </div>
    
    <!-- Applications by Status -->
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Applications Status</h3>
        <div class="h-56 sm:h-64 lg:h-72 flex items-center justify-center">
            <canvas id="applicationsChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
    <!-- Recent Team Members -->
    <div class="xl:col-span-2 bg-white rounded-lg shadow-sm p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <h3 class="text-base font-semibold text-gray-800">Recent Team Members</h3>
            <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">View All</a>
        </div>
        <div class="overflow-x-auto -mx-2 sm:mx-0">
        <table class="w-full min-w-[560px] sm:min-w-0">
            <thead>
                <tr class="text-left border-b border-gray-100">
                    <th class="text-xs font-medium text-gray-500 uppercase tracking-wider pb-3">Name</th>
                    <th class="text-xs font-medium text-gray-500 uppercase tracking-wider pb-3">Unit</th>
                    <th class="text-xs font-medium text-gray-500 uppercase tracking-wider pb-3">Designation</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($recentUsers as $user)
                <tr class="border-b border-gray-50 last:border-0">
                    <td class="py-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-semibold mr-3">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
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
    </div>

    <!-- Quick Actions -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('users.create') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-plus text-gray-400 mr-3"></i>
                    <span class="text-sm text-gray-700">Add Team Member</span>
                </a>
                <a href="{{ route('units.create') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-plus text-gray-400 mr-3"></i>
                    <span class="text-sm text-gray-700">Add Unit</span>
                </a>
                <a href="{{ route('events.create') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-plus text-gray-400 mr-3"></i>
                    <span class="text-sm text-gray-700">Create Event</span>
                </a>
                <a href="{{ route('team-applications.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-clipboard-list text-gray-400 mr-3"></i>
                    <span class="text-sm text-gray-700">Review Applications</span>
                </a>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Applications</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $totalApplications }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Approved</span>
                    <span class="text-sm font-semibold text-green-600">{{ $applicationStatuses['approved'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Rejected</span>
                    <span class="text-sm font-semibold text-red-600">{{ $applicationStatuses['rejected'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Collaborations</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $totalCollaborations }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Messages</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $totalContacts }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = 'system-ui, -apple-system, sans-serif';
    Chart.defaults.color = '#6b7280';
    
    var grayColor = '#6b7280';
    var grayLight = '#9ca3af';
    
    var monthLabels = {!! json_encode($monthLabels) !!};
    var monthData = {!! json_encode($monthData) !!};
    
    // Events by Month - Line Chart
    var eventsCtx = document.getElementById('eventsChart');
    if (eventsCtx) {
        new Chart(eventsCtx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Events',
                    data: monthData,
                    borderColor: '#22c55e',
                    backgroundColor: '#22c55e10',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#22c55e',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
    
    // Applications by Status - Doughnut Chart
    var appPending = {{ $applicationStatuses['pending'] ?? 0 }};
    var appApproved = {{ $applicationStatuses['approved'] ?? 0 }};
    var appRejected = {{ $applicationStatuses['rejected'] ?? 0 }};
    
    var appCtx = document.getElementById('applicationsChart');
    if (appCtx) {
        new Chart(appCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [appPending, appApproved, appRejected],
                    backgroundColor: ['#fbbf24', '#22c55e', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            boxWidth: 8,
                            boxHeight: 8,
                            font: { size: 11 },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
