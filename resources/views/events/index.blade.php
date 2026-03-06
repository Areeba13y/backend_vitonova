@extends('layouts.master')

@section('title', 'Events')
@section('page_title', 'Event Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <h4 class="text-xl font-semibold text-gray-800">Events</h4>
        <button onclick="openAddEventModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Event
        </button>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="eventsTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($events as $event)
                        @php
                            $eventData = [
                                'id' => $event->id,
                                'image' => $event->image,
                                'image_url' => asset($event->image),
                                'category' => $event->category,
                                'title' => $event->title,
                                'description' => $event->description,
                                'submission_deadline' => $event->submission_deadline?->format('Y-m-d'),
                                'event_date' => $event->event_date?->format('Y-m-d'),
                                'created_at' => $event->created_at?->format('Y-m-d H:i'),
                                'updated_at' => $event->updated_at?->format('Y-m-d H:i'),
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50" id="event-row-{{ $event->id }}" data-event-id="{{ $event->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="w-16 h-12 object-cover rounded border border-gray-200">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->category }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $event->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->submission_deadline?->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->event_date?->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="viewEvent(this)"
                                            class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors"
                                            title="View Event"
                                            data-event="{{ json_encode($eventData, JSON_HEX_APOS | JSON_HEX_QUOT) }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="editEvent(this)"
                                            class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50 transition-colors"
                                            title="Edit Event"
                                            data-event="{{ json_encode($eventData, JSON_HEX_APOS | JSON_HEX_QUOT) }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteEvent({{ $event->id }}, {{ json_encode($event->title) }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                            title="Delete Event">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-events-row">
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="mt-6">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>

@include('components.event-modal')
@endsection
