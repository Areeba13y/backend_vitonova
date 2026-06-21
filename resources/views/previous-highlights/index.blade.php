{{-- resources/views/previous-highlights/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Previous Highlights')
@section('page_title', 'Previous Highlights')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">All Highlights</h2>
                <p class="text-sm text-gray-500 mt-1">Manage previous event highlights</p>
            </div>
        </div>
        <a href="{{ route('previous-highlights.create') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-5 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm shadow-sm transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Highlight
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-3 sm:p-4 lg:p-6 relative">
        <div class="overflow-x-auto lg:overflow-visible">
        <table id="highlightsTable" class="w-full min-w-[700px]">
            <thead>
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">#</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Image</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Title</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">URL</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Created At</th>
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
    function applyHighlightsTableResponsiveClasses() {
        const wrapper = $('#highlightsTable_wrapper');
        if (!wrapper.length) {
            return;
        }

        wrapper.find('> .highlights-table-toolbar').addClass('flex flex-col lg:flex-row lg:items-center gap-3');
        wrapper.find('.highlights-length').addClass('w-full');
        wrapper.find('.highlights-filter').addClass('w-full lg:w-auto lg:ml-auto');
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
        wrapper.find('.highlights-table-footer').addClass('flex flex-col gap-3 md:flex-row md:items-center md:justify-between');
        wrapper.find('.dataTables_info').addClass('!float-none text-center md:text-left !text-sm !text-gray-500');
        wrapper.find('.dataTables_paginate').addClass('!float-none flex justify-center md:justify-end');
    }

    // Initialize DataTable
    $('#highlightsTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route("previous-highlights.datatable") }}',
            type: 'GET',
            dataType: 'json',
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
                Toast.fire({
                    icon: 'error',
                    title: 'Error loading data. Please refresh the page.'
                });
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                width: '50px'
            },
            { 
                data: 'image', 
                name: 'image',
                orderable: false,
                searchable: false
            },
            { 
                data: 'title', 
                name: 'title' 
            },
            { 
                data: 'url', 
                name: 'url',
                orderable: false,
                searchable: false
            },
            { 
                data: 'created_at', 
                name: 'created_at' 
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false 
            }
        ],
        order: [[4, 'desc']],
        autoWidth: false,
        dom: '<"highlights-table-toolbar mb-4"<"highlights-length"l><"highlights-filter"f>>rt<"highlights-table-footer mt-4"ip>',
        initComplete: function() {
            applyHighlightsTableResponsiveClasses();
        },
        drawCallback: function() {
            applyHighlightsTableResponsiveClasses();
        },
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-green-500 text-lg mr-2"></i> Loading data...</div>',
            search: "",
            searchPlaceholder: "Search highlights...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ highlights",
            emptyTable: '<div class="text-center py-8"><i class="fas fa-star text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No highlights found</p></div>',
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

function deleteHighlight(id, title) {
    // Close all hamburger menus
    $('.actions-menu').removeClass('active');
    $('.actions-dropdown').hide();
    $('.actions-menu .hamburger input').prop('checked', false);
    
    Swal.fire({
        title: 'Delete Highlight',
        text: 'Are you sure you want to delete "' + title + '"? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("previous-highlights.index") }}/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Highlight deleted successfully!'
                    });
                    $('#highlightsTable').DataTable().ajax.reload();
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message || 'Error deleting highlight'
                    });
                }
            })
            .catch(error => {
                Toast.fire({
                    icon: 'error',
                    title: 'Error deleting highlight'
                });
                console.error('Delete error:', error);
            });
        }
    });
}
</script>
@endsection