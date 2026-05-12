@extends('layouts.master')

@section('title', $event->title)
@section('page_title', 'Event Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">{{ $event->title }}</h2>
        <p class="text-sm text-gray-500 mt-1">Event details</p>
    </div>
    <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Events
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($event->image)
            <div class="h-64 overflow-hidden">
                <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            </div>
            @endif
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-full">{{ $event->category }}</span>
                    <span class="text-sm text-gray-500">Created {{ $event->created_at->format('M d, Y') }}</span>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                <p class="text-gray-600 mb-6 whitespace-pre-line">{{ $event->description }}</p>
                
                <div class="flex flex-wrap gap-6 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Submission Deadline</p>
                        <p class="font-medium text-gray-800">{{ $event->submission_deadline->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Event Date</p>
                        <p class="font-medium text-gray-800">{{ $event->event_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-users text-indigo-500 mr-3"></i>
                        <span class="text-gray-600">Registrations</span>
                    </div>
                    <span class="text-xl font-bold text-gray-800">{{ $event->registrations_count ?? $event->registrations->count() }}</span>
                </div>
            </div>
            
            <div class="mt-6 flex flex-col space-y-3">
                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Event
                </a>
                <button onclick="deleteEvent({{ $event->id }}, '{{ addslashes($event->title) }}')" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Event
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteEvent(id, title) {
    Swal.fire({
        title: 'Delete Event',
        text: 'Are you sure you want to delete "' + title + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${baseUrl}/events/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Event deleted successfully!' });
                    setTimeout(function() {
                        window.location.href = '{{ route("events.index") }}';
                    }, 1500);
                }
            });
        }
    });
}
</script>
@endsection
