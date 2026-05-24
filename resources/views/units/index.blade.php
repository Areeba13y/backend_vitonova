@extends('layouts.master')

@section('title', 'Units')
@section('page_title', 'Units')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">All Units</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your organizational units</p>
            </div>
        </div>
        <a href="{{ route('units.create') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-5 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm shadow-sm transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Unit
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-3 sm:p-4 lg:p-6 relative">
        <div class="overflow-x-auto lg:overflow-visible" id="unitsTableScrollWrap">
        <table id="unitsTable" class="w-full min-w-[700px]">
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
</div>

<script>
$(document).ready(function() {
    function applyUnitsTableResponsiveClasses() {
        const wrapper = $('#unitsTable_wrapper');
        if (!wrapper.length) {
            return;
        }

        wrapper.find('> .units-table-toolbar').addClass('flex flex-col lg:flex-row lg:items-center gap-3');
        wrapper.find('.units-length').addClass('w-full');
        wrapper.find('.units-filter').addClass('w-full lg:w-auto lg:ml-auto');
        wrapper.find('.units-export').addClass('w-full lg:w-auto lg:ml-3');

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

        wrapper.find('.units-table-footer').addClass('flex flex-col gap-3 md:flex-row md:items-center md:justify-between');
        wrapper.find('.dataTables_info').addClass('!float-none text-center md:text-left !text-sm !text-gray-500');
        wrapper.find('.dataTables_paginate').addClass('!float-none flex justify-center md:justify-end');
    }

    $('#unitsTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route("units.datatable") }}',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'members_count', name: 'members_count', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        autoWidth: false,
        dom: '<"units-table-toolbar mb-4"<"units-length"l><"units-filter"f><"units-export"B>>rt<"units-table-footer mt-4"ip>',
        buttons: [
            { extend: 'csv', className: '', text: '<i class="fas fa-file-csv mr-2"></i>CSV' }
        ],
        initComplete: function() {
            applyUnitsTableResponsiveClasses();
        },
        drawCallback: function() {
            applyUnitsTableResponsiveClasses();
        },
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-green-500 text-lg mr-2"></i> Loading data...</div>',
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
