@extends('layouts.master')

@section('title', 'Users')
@section('page_title', 'Users')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">All Users</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your team members</p>
    </div>
    <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm shadow-sm transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Add New User
    </a>
</div>

<!-- Filter Row -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap gap-4 items-center">
    <div class="flex items-center space-x-2">
        <label class="text-sm text-gray-600">Unit:</label>
        <select id="unitFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Units</option>
            @foreach(\App\Models\Unit::orderBy('name')->get() as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- DataTable Card -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 relative">
        <div id="usersTableLoading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg" style="display: none;">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading data...</span>
            </div>
        </div>
        <table id="usersTable" class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">User</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Email</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Unit</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Designation</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Contact</th>
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
    let usersTable;
    
    function initDataTable(unitId = '') {
        if (usersTable) {
            usersTable.destroy();
        }
        
        usersTable = $('#usersTable').DataTable({
            serverSide: true,
            ajax: {
                url: '{{ route("users.datatable") }}',
                type: 'GET',
                beforeSend: function() {
                    $('#usersTableLoading').show();
                },
                data: function(d) {
                    d.unit_id = unitId;
                },
                complete: function() {
                    $('#usersTableLoading').hide();
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTable Error:', xhr.responseText);
                    $('#usersTableLoading').hide();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'unit', name: 'unit.name' },
                { data: 'designation', name: 'designation' },
                { data: 'contact', name: 'contact' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[0, 'asc']],
            pageLength: 10,
            dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex-1"f><"flex items-center"B>>rtip',
            buttons: [
                { extend: 'csv', className: 'mr-2', text: '<i class="fas fa-file-csv mr-1"></i> CSV' }
            ],
            language: {
                processing: '<div class="flex items-center justify-center py-8"><i class="fas fa-spinner fa-spin text-indigo-500 text-2xl mr-3"></i> Loading data...</div>',
                search: "",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                emptyTable: '<div class="text-center py-8"><i class="fas fa-users text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No users found</p></div>',
                zeroRecords: '<div class="text-center py-8"><i class="fas fa-search text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No matching records found</p></div>',
                paginate: {
                    first: '<i class="fas fa-chevrons-left"></i>',
                    last: '<i class="fas fa-chevrons-right"></i>',
                    next: '<i class="fas fa-chevron-right"></i>',
                    previous: '<i class="fas fa-chevron-left"></i>'
                }
            }
        });
    }
    
    initDataTable();
    
    $('#unitFilter').on('change', function() {
        initDataTable($(this).val());
    });
});

function deleteUser(id, name) {
    Swal.fire({
        title: 'Delete User',
        text: 'Are you sure you want to delete "' + name + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/users/' + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({ icon: 'success', title: 'User deleted successfully!' });
                        $('#usersTable').DataTable().ajax.reload();
                    }
                }
            });
        }
    });
}
</script>
@endsection
