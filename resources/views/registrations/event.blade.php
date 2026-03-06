@extends('layouts.master')

@section('title', 'Registrations - ' . $event->title)
@section('page_title', 'Event Registrations')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200 gap-4">
        <div>
            <a href="{{ route('event-registrations.index') }}" class="inline-flex items-center text-sm text-green-700 hover:text-green-800 mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Event Registrations
            </a>
            <h4 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h4>
            <p class="text-sm text-gray-500 mt-1">Participant list for this event.</p>
        </div>
        <form method="GET" action="{{ route('event-registrations.event', $event) }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name, email, university..." class="w-full sm:w-72 pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"></path>
                </svg>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-medium transition-colors">
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('event-registrations.event', $event) }}" class="px-4 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium transition-colors text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider uppercase text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider uppercase text-gray-500">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider uppercase text-gray-500">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider uppercase text-gray-500">University</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider uppercase text-gray-500">Country</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider uppercase text-gray-500">Registered At</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider uppercase text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($registrations as $registration)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $registration->user?->name ?? 'Unknown User' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $registration->user?->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $registration->user?->contact ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $registration->university_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $registration->country }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $registration->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('event-registrations.show', ['event' => $event->id, 'registration' => $registration->id]) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No registrations found for this event.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registrations->hasPages())
            <div class="mt-6">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
