@extends('layouts.master')

@section('title', 'Users')
@section('page_title', 'Users')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">All Users</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your team members</p>
            </div>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-5 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm shadow-sm transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add New User
        </a>
    </div>
</div>

<!-- Filter Row -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 sm:mb-6 flex flex-wrap gap-4 items-center">
    <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
        <label class="text-sm text-gray-600">Unit:</label>
        <select id="unitFilter" class="w-full sm:w-72 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">All Units</option>
            @foreach(\App\Models\Unit::orderBy('name')->get() as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- DataTable Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-3 sm:p-4 lg:p-6 relative">
        <div class="overflow-x-auto lg:overflow-visible" id="usersTableScrollWrap">
        <table id="usersTable" class="w-full min-w-[860px]">
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
</div>

<script>
$(document).ready(function() {
    let usersTable;

    function applyUsersTableResponsiveClasses() {
        const wrapper = $('#usersTable_wrapper');
        if (!wrapper.length) {
            return;
        }

        wrapper.find('> .users-table-toolbar').addClass('flex flex-col lg:flex-row lg:items-center gap-3');
        wrapper.find('.users-length').addClass('w-full');
        wrapper.find('.users-filter').addClass('w-full lg:w-auto lg:ml-auto');
        wrapper.find('.users-export').addClass('w-full lg:w-auto lg:ml-3');

        wrapper.find('.dataTables_length label').addClass('flex items-center gap-2 text-sm font-medium text-gray-600');
        wrapper.find('.dataTables_length select').addClass('!h-11 !rounded-xl !border-gray-200 !shadow-none bg-gray-50');

        wrapper.find('.dataTables_filter').addClass('lg:ml-0');
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

        wrapper.find('.users-table-footer').addClass('flex flex-col gap-3 md:flex-row md:items-center md:justify-between');
        wrapper.find('.dataTables_info').addClass('!float-none text-center md:text-left !text-sm !text-gray-500');
        wrapper.find('.dataTables_paginate').addClass('!float-none flex justify-center md:justify-end');
    }
    
    const urlParams = new URLSearchParams(window.location.search);
    const initialUnitId = urlParams.get('unit_id') || '';
    
    if (initialUnitId) {
        $('#unitFilter').val(initialUnitId);
    }
    
    function initDataTable(unitId = '') {
        if (usersTable) {
            usersTable.destroy();
        }
        
        usersTable = $('#usersTable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route("users.datatable") }}',
                type: 'GET',
                data: function(d) {
                    d.unit_id = unitId;
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTable Error:', xhr.responseText);
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
            autoWidth: false,
            dom: '<"users-table-toolbar mb-4"<"users-length"l><"users-filter"f><"users-export"B>>rt<"users-table-footer mt-4"ip>',
            buttons: [
                { extend: 'csv', className: '', text: '<i class="fas fa-file-csv mr-2"></i>CSV' }
            ],
            initComplete: function() {
                applyUsersTableResponsiveClasses();
            },
            drawCallback: function() {
                applyUsersTableResponsiveClasses();
            },
            language: {
                processing: '<div class="flex items-center justify-center py-8"><i class="fas fa-spinner fa-spin text-green-500 text-2xl mr-3"></i> Loading data...</div>',
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
    
    initDataTable(initialUnitId);
    
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
