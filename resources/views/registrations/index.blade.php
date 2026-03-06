@extends('layouts.master')

@section('title', 'Event Registrations')
@section('page_title', 'Event Registrations')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h4 class="text-xl font-semibold text-gray-800">Event Registrations</h4>
            <p class="text-sm text-gray-500 mt-1">Track participation and open event-wise registration lists.</p>
        </div>
        <a href="{{ route('event-registrations.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-medium transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Reload
        </a>
    </div>

    <div class="p-6">
        @if($events->isEmpty())
            <div class="bg-white rounded-xl border border-dashed border-gray-300 p-10 text-center">
                <p class="text-gray-700 font-semibold text-lg">No events found</p>
                <p class="text-gray-500 mt-1">Create events first to start receiving registrations.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <a href="{{ route('event-registrations.event', $event) }}" class="group block bg-white rounded-xl border border-gray-200 hover:border-green-400 hover:shadow-lg transition-all duration-200 overflow-hidden">
                        <div class="p-5">
                            @if($event->image)
                                <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-full h-40 rounded-lg border border-gray-200 bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center">
                                    <span class="text-green-700 text-sm font-semibold">No Image</span>
                                </div>
                            @endif

                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-xs font-semibold tracking-wide text-green-700 uppercase">{{ $event->category ?? 'General' }}</p>
                                <p class="text-xs text-gray-500">{{ $event->event_date?->format('Y-m-d') ?? '-' }}</p>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mt-2">{{ $event->title ?? 'Untitled Event' }}</h3>
                            <div class="mt-4 bg-gray-50 group-hover:bg-green-50 rounded-lg p-3 border border-gray-200 group-hover:border-green-200 transition-colors">
                                <p class="text-xs text-gray-500">Total Registrations</p>
                                <p class="text-2xl font-extrabold text-gray-900">{{ $event->registrations_count }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($events->hasPages())
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
