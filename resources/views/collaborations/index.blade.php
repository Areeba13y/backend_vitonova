@extends('layouts.master')

@section('title', 'Collaborations')
@section('page_title', 'Collaborations')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">All Collaborations</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your partnerships</p>
    </div>
    <a href="{{ route('collaborations.create') }}" class="inline-flex items-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium text-sm shadow-sm transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Add Collaboration
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 relative">
        <div id="collaborationsTableLoading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg" style="display: none;">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-green-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading data...</span>
            </div>
        </div>
        <table id="collaborationsTable" class="w-full">
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

@include('components.collaboration-modal')

<script>
$(document).ready(function() {
    $('#collaborationsTable').DataTable({
        serverSide: true,
        ajax: {
            url: '{{ route("collaborations.datatable") }}',
            type: 'GET',
            beforeSend: function() {
                $('#collaborationsTableLoading').show();
            },
            complete: function() {
                $('#collaborationsTableLoading').hide();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
                $('#collaborationsTableLoading').hide();
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
        dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex-1"f><"flex items-center"B>>rtip',
        buttons: [
            { extend: 'csv', className: 'mr-2', text: '<i class="fas fa-file-csv mr-1"></i> CSV' }
        ],
        language: {
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
