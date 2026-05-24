@extends('layouts.master')

@section('title', 'Registrations - ' . $event->title)
@section('page_title', 'Event Registrations')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center p-4 sm:p-6 border-b border-gray-200">
        <div>
            <a href="{{ route('events.index') }}" class="inline-flex items-center text-sm text-green-700 hover:text-green-800 mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Events
            </a>
            <h4 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h4>
            <p class="text-sm text-gray-500 mt-1">Participant list for this event.</p>
        </div>
        <form method="GET" action="{{ route('event-registrations.event', $event) }}" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <div class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name, email, university..." class="w-full sm:w-72 lg:w-80 pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"></path>
                </svg>
            </div>
            <select name="event_filter" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="upcoming" {{ ($eventFilter ?? 'upcoming') === 'upcoming' ? 'selected' : '' }}>Upcoming Events</option>
                <option value="past" {{ ($eventFilter ?? '') === 'past' ? 'selected' : '' }}>Past Events</option>
            </select>
            <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-medium transition-colors">
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('event-registrations.event', $event) }}" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium transition-colors text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="p-4 sm:p-6">
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
