@extends('layouts.master')

@section('title', 'Collaborations')
@section('page_title', 'Collaborations')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-handshake"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">All Collaborations</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your partnerships</p>
            </div>
        </div>
        <a href="{{ route('collaborations.create') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-5 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm shadow-sm transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Collaboration
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-3 sm:p-4 lg:p-6 relative">
        <div class="overflow-x-auto lg:overflow-visible">
        <table id="collaborationsTable" class="w-full min-w-[900px]">
            <thead>
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Logo</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Organization</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Representative</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
            </tbody>
        </table>
        </div>
    </div>
</div>

@include('components.collaboration-modal')

<script>
$(document).ready(function() {
    function applyCollaborationsTableResponsiveClasses() {
        const wrapper = $('#collaborationsTable_wrapper');
        if (!wrapper.length) {
            return;
        }

        wrapper.find('> .collaborations-table-toolbar').addClass('flex flex-col lg:flex-row lg:items-center gap-3');
        wrapper.find('.collaborations-length').addClass('w-full');
        wrapper.find('.collaborations-filter').addClass('w-full lg:w-auto lg:ml-auto');
        wrapper.find('.collaborations-export').addClass('w-full lg:w-auto lg:ml-3');
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
        wrapper.find('.collaborations-table-footer').addClass('flex flex-col gap-3 md:flex-row md:items-center md:justify-between');
        wrapper.find('.dataTables_info').addClass('!float-none text-center md:text-left !text-sm !text-gray-500');
        wrapper.find('.dataTables_paginate').addClass('!float-none flex justify-center md:justify-end');
    }

    $('#collaborationsTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route("collaborations.datatable") }}',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
            }
        },
        columns: [
            { 
                data: 'logo', 
                name: 'logo',
                orderable: false,
                searchable: false,
                render: function(data) {
                    if (data) {
                        return '<img src="' + data + '" alt="Logo" class="w-12 h-12 object-cover rounded">';
                    }
                    return '<div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center"><i class="fas fa-building text-gray-400"></i></div>';
                }
            },
            { data: 'organization_name', name: 'organization_name' },
            { data: 'representative', name: 'representative_name' },
            { 
                data: 'is_active', 
                name: 'is_active',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data) {
                        return '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Active</span>';
                    }
                    return '<span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">Inactive</span>';
                }
            },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        autoWidth: false,
        dom: '<"collaborations-table-toolbar mb-4"<"collaborations-length"l><"collaborations-filter"f><"collaborations-export"B>>rt<"collaborations-table-footer mt-4"ip>',
        buttons: [
            { extend: 'csv', className: '', text: '<i class="fas fa-file-csv mr-2"></i>CSV' }
        ],
        initComplete: function() {
            applyCollaborationsTableResponsiveClasses();
        },
        drawCallback: function() {
            applyCollaborationsTableResponsiveClasses();
        },
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-green-500 text-lg mr-2"></i> Loading data...</div>',
            search: "",
            searchPlaceholder: "Search collaborations...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ collaborations",
            emptyTable: '<div class="text-center py-8"><i class="fas fa-handshake text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No collaborations found</p></div>',
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

function toggleCollaborationActive(id) {
    fetch(`/collaborations/${id}/toggle-active`, {
        method: 'PATCH',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        Toast.fire({ icon: 'success', title: data.message });
        $('#collaborationsTable').DataTable().ajax.reload();
    });
}

function deleteCollaboration(id, name) {
    Swal.fire({
        title: 'Delete Collaboration',
        text: 'Are you sure you want to delete "' + name + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/collaborations/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Collaboration deleted successfully!' });
                    $('#collaborationsTable').DataTable().ajax.reload();
                }
            });
        }
    });
}
</script>
@endsection
