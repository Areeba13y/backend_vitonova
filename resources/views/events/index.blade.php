@extends('layouts.master')

@section('title', 'Events')
@section('page_title', 'Events')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">All Events</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your events</p>
            </div>
        </div>
        <a href="{{ route('events.create') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-5 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm shadow-sm transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Event
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-3 sm:p-4 lg:p-6 relative">
        <div class="overflow-x-auto lg:overflow-visible">
        <table id="eventsTable" class="w-full min-w-[1080px]">
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
</div>

@include('components.event-modal')

<script>
$(document).ready(function() {
    function applyEventsTableResponsiveClasses() {
        const wrapper = $('#eventsTable_wrapper');
        if (!wrapper.length) {
            return;
        }

        wrapper.find('> .events-table-toolbar').addClass('flex flex-col lg:flex-row lg:items-center gap-3');
        wrapper.find('.events-length').addClass('w-full');
        wrapper.find('.events-filter').addClass('w-full lg:w-auto lg:ml-auto');
        wrapper.find('.events-export').addClass('w-full lg:w-auto lg:ml-3');
        wrapper.find('.dataTables_length label').addClass('flex items-center gap-2 text-sm font-medium text-gray-600');
        wrapper.find('.dataTables_length select').addClass('!h-11 !rounded-xl !border-gray-200 !shadow-none bg-gray-50');
        wrapper.find('.dataTables_filter label').addClass('block w-full');
        wrapper.find('.dataTables_filter input').addClass('!w-full !h-11 !rounded-xl !border-gray-200 !shadow-none bg-gray-50');
        const searchInput = wrapper.find('.dataTables_filter input');
        if (window.innerWidth >= 1280) {
            searchInput.css('width', '20rem');
        } else if (window.innerWidth >= 1024) {
            searchInput.css('width', '18rem');
        } else {
            searchInput.css('width', '100%');
        }
        wrapper.find('.dt-buttons').addClass('w-full flex justify-center lg:justify-end');
        wrapper.find('.dt-button').addClass('!w-full md:!w-auto !h-11 !mx-0 !rounded-xl !px-5 !font-semibold !text-sm');
        wrapper.find('.events-table-footer').addClass('flex flex-col gap-3 md:flex-row md:items-center md:justify-between');
        wrapper.find('.dataTables_info').addClass('!float-none text-center md:text-left !text-sm !text-gray-500');
        wrapper.find('.dataTables_paginate').addClass('!float-none flex justify-center md:justify-end');
    }

    $('#eventsTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route("events.datatable") }}',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
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
        autoWidth: false,
        dom: '<"events-table-toolbar mb-4"<"events-length"l><"events-filter"f><"events-export"B>>rt<"events-table-footer mt-4"ip>',
        buttons: [
            { extend: 'csv', className: '', text: '<i class="fas fa-file-csv mr-2"></i>CSV' }
        ],
        initComplete: function() {
            applyEventsTableResponsiveClasses();
        },
        drawCallback: function() {
            applyEventsTableResponsiveClasses();
        },
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-green-500 text-lg mr-2"></i> Loading data...</div>',
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
