@extends('layouts.master')

@section('title', 'Events')
@section('page_title', 'Events')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">All Events</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your events</p>
    </div>
    <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm shadow-sm transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Add Event
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 relative">
        <div id="eventsTableLoading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg" style="display: none;">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading data...</span>
            </div>
        </div>
        <table id="eventsTable" class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Image</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Title</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Category</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Deadline</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Event Date</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Registrations</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
            </tbody>
        </table>
    </div>
</div>

@include('components.event-modal')

<script>
$(document).ready(function() {
    $('#eventsTable').DataTable({
        serverSide: true,
        ajax: {
            url: '{{ route("events.datatable") }}',
            type: 'GET',
            beforeSend: function() {
                $('#eventsTableLoading').show();
            },
            complete: function() {
                $('#eventsTableLoading').hide();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
                $('#eventsTableLoading').hide();
            }
        },
        columns: [
            { 
                data: 'image', 
                name: 'image',
                orderable: false,
                searchable: false,
                render: function(data) {
                    if (data) {
                        return '<img src="' + data + '" alt="Event" class="w-12 h-10 object-cover rounded">';
                    }
                    return '<div class="w-12 h-10 rounded bg-gray-100 flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>';
                }
            },
            { data: 'title', name: 'title' },
            { data: 'category', name: 'category' },
            { data: 'submission_deadline', name: 'submission_deadline' },
            { data: 'event_date', name: 'event_date' },
            { data: 'registrations_count', name: 'registrations_count', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex-1"f><"flex items-center"B>>rtip',
        buttons: [
            { extend: 'csv', className: 'mr-2', text: '<i class="fas fa-file-csv mr-1"></i> CSV' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search events...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ events",
            emptyTable: '<div class="text-center py-8"><i class="fas fa-calendar text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No events found</p></div>',
            zeroRecords: '<div class="text-center py-8"><i class="fas fa-search text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No matching records found</p></div>',
            paginate: {
                first: '<i class="fas fa-chevrons-left"></i>',
                last: '<i class="fas fa-chevrons-right"></i>',
                next: '<i class="fas fa-chevron-right"></i>',
                previous: '<i class="fas fa-chevron-left"></i>'
            }
        }
    });
});

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
            fetch(`/events/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Event deleted successfully!' });
                    $('#eventsTable').DataTable().ajax.reload();
                }
            });
        }
    });
}
</script>
@endsection
