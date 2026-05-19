@extends('layouts.master')

@section('title', 'Units')
@section('page_title', 'Units')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">All Units</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your organizational units</p>
    </div>
    <a href="{{ route('units.create') }}" class="inline-flex items-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium text-sm shadow-sm transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Add Unit
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 relative">
        <div id="unitsTableLoading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg" style="display: none;">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-green-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading data...</span>
            </div>
        </div>
        <table id="unitsTable" class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Name</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Members</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#unitsTable').DataTable({
        serverSide: true,
        ajax: {
            url: '{{ route("units.datatable") }}',
            type: 'GET',
            beforeSend: function() {
                $('#unitsTableLoading').show();
            },
            complete: function() {
                $('#unitsTableLoading').hide();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
                $('#unitsTableLoading').hide();
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'members_count', name: 'members_count', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex-1"f><"flex items-center"B>>rtip',
        buttons: [
            { extend: 'csv', className: 'mr-2', text: '<i class="fas fa-file-csv mr-1"></i> CSV' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search units...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ units",
            emptyTable: '<div class="text-center py-8"><i class="fas fa-building text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No units found</p></div>',
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

function deleteUnit(id) {
    Swal.fire({
        title: 'Delete Unit',
        text: 'Are you sure you want to delete this unit?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${baseUrl}/units/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success || data.status === 'success') {
                        Toast.fire({ icon: 'success', title: 'Unit deleted successfully!' });
                        $('#unitsTable').DataTable().ajax.reload();
                    }
                }
            });
        }
    });
}
</script>
@endsection
