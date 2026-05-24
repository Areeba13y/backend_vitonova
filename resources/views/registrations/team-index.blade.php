@extends('layouts.master')

@section('title', 'Team Applications')
@section('page_title', 'Team Applications')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
<style>
    .approve-unit-select + .ts-wrapper .ts-control {
        min-height: 44px;
        border-radius: 0.75rem;
        border-color: #d1d5db;
        background-color: #f9fafb;
        box-shadow: none;
        padding: 0.55rem 0.75rem;
    }

    .approve-unit-select + .ts-wrapper.focus .ts-control {
        border-color: #22c55e;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
        background-color: #ffffff;
    }

    .approve-unit-select + .ts-wrapper .ts-dropdown {
        border-radius: 0.75rem;
        border: 1px solid #d1d5db;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        overflow: hidden;
        margin-top: 0.4rem;
    }

    .approve-unit-select + .ts-wrapper .ts-dropdown .ts-dropdown-content {
        max-height: 210px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #9ca3af transparent;
    }

    .approve-unit-select + .ts-wrapper .ts-dropdown .ts-dropdown-content::-webkit-scrollbar {
        width: 4px;
    }

    .approve-unit-select + .ts-wrapper .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb {
        background-color: #9ca3af;
        border-radius: 9999px;
    }

    .approve-unit-select + .ts-wrapper .ts-dropdown .option {
        padding: 0.65rem 0.9rem;
    }

    .approve-unit-select + .ts-wrapper .ts-dropdown .active {
        background-color: #ecfdf5;
        color: #166534;
    }
</style>
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-plus"></i>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Applications</h2>
            <p class="text-sm text-gray-500 mt-1">Manage team applications</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-3 sm:p-4 lg:p-6 relative">
        <div class="overflow-x-auto lg:overflow-visible">
        <table id="applicationsTable" class="w-full min-w-[900px]">
            <thead>
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Name</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Email</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Position</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Date</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
            </tbody>
        </table>
        </div>
    </div>
</div>

<div id="approveModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative w-full max-w-md mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Approve Application</h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-4 sm:p-6">
                <input type="hidden" id="approveApplicationId">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label for="approveUnitId" class="block text-sm font-medium text-gray-700">Unit *</label>
                        <button type="button" id="openCreateUnitModalBtn" class="inline-flex items-center text-xs font-medium px-2.5 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                            <i class="fas fa-plus mr-1"></i>
                            Add Unit
                        </button>
                    </div>
                    <select id="approveUnitId" class="approve-unit-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label for="approveDesignation" class="block text-sm font-medium text-gray-700 mb-1">Designation *</label>
                    <input id="approveDesignation" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter designation" required>
                </div>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
                    <button type="button" onclick="approveApplication()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-medium">Approve</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="createUnitModal" class="fixed inset-0 bg-black/50 h-full w-full z-[60] hidden flex items-center justify-center">
    <div class="relative w-full max-w-sm mx-4">
        <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h4 class="text-base font-semibold text-gray-800">Create New Unit</h4>
                <button type="button" id="closeCreateUnitModalBtn" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <label for="quickUnitName" class="block text-sm font-medium text-gray-700 mb-1">Unit Name *</label>
                <input id="quickUnitName" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter unit name">
                <p id="quickUnitError" class="text-xs text-red-500 mt-1 hidden"></p>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" id="cancelCreateUnitBtn" class="px-3.5 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="button" id="saveCreateUnitBtn" class="px-3.5 py-2 text-sm font-medium rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors">
                        <span id="saveCreateUnitText"><i class="fas fa-save mr-1"></i>Create</span>
                        <span id="saveCreateUnitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Creating...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
const approveUrlTemplate = @json(route('team-applications.approve', ['id' => '__ID__']));
const deleteUrlTemplate = @json(route('team-applications.destroy', ['id' => '__ID__']));
const quickCreateUnitUrl = @json(route('units.store'));
let approveUnitSelect = null;

$(document).ready(function() {
    if (window.TomSelect) {
        approveUnitSelect = new TomSelect('#approveUnitId', {
            create: false,
            maxOptions: null,
            allowEmptyOption: true,
            placeholder: 'Select Unit',
            closeAfterSelect: true,
            render: {
                no_results: function() {
                    return '<div class="px-3 py-2 text-sm text-gray-500">No unit found</div>';
                }
            }
        });
    }

    function applyApplicationsTableResponsiveClasses() {
        const wrapper = $('#applicationsTable_wrapper');
        if (!wrapper.length) {
            return;
        }

        wrapper.find('> .applications-table-toolbar').addClass('flex flex-col lg:flex-row lg:items-center gap-3');
        wrapper.find('.applications-length').addClass('w-full');
        wrapper.find('.applications-filter').addClass('w-full lg:w-auto lg:ml-auto');
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
        wrapper.find('.applications-table-footer').addClass('flex flex-col gap-3 md:flex-row md:items-center md:justify-between');
        wrapper.find('.dataTables_info').addClass('!float-none text-center md:text-left !text-sm !text-gray-500');
        wrapper.find('.dataTables_paginate').addClass('!float-none flex justify-center md:justify-end');
    }

    $('#applicationsTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: @json(route('team-applications.datatable')),
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'position', name: 'position' },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'date', name: 'date', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        autoWidth: false,
        dom: '<"applications-table-toolbar mb-4"<"applications-length"l><"applications-filter"f>>rt<"applications-table-footer mt-4"ip>',
        initComplete: function() {
            applyApplicationsTableResponsiveClasses();
        },
        drawCallback: function() {
            applyApplicationsTableResponsiveClasses();
        },
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-green-500 text-lg mr-2"></i> Loading data...</div>',
            search: "",
            searchPlaceholder: "Search applications...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ applications",
            emptyTable: '<div class="text-center py-8"><i class="fas fa-users text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No applications found</p></div>',
            zeroRecords: '<div class="text-center py-8"><i class="fas fa-search text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No matching records found</p></div>',
            paginate: {
                first: '<i class="fas fa-chevrons-left"></i>',
                last: '<i class="fas fa-chevrons-right"></i>',
                next: '<i class="fas fa-chevron-right"></i>',
                previous: '<i class="fas fa-chevron-left"></i>'
            }
        }
    });

    function openCreateUnitModal() {
        document.getElementById('quickUnitName').value = '';
        document.getElementById('quickUnitError').classList.add('hidden');
        document.getElementById('createUnitModal').classList.remove('hidden');
        document.getElementById('createUnitModal').classList.add('flex');
        setTimeout(function() {
            document.getElementById('quickUnitName').focus();
        }, 50);
    }

    function closeCreateUnitModal() {
        document.getElementById('createUnitModal').classList.remove('flex');
        document.getElementById('createUnitModal').classList.add('hidden');
    }

    function setCreateUnitLoading(isLoading) {
        const btn = document.getElementById('saveCreateUnitBtn');
        const text = document.getElementById('saveCreateUnitText');
        const loading = document.getElementById('saveCreateUnitLoading');
        btn.disabled = isLoading;
        text.classList.toggle('hidden', isLoading);
        loading.classList.toggle('hidden', !isLoading);
    }

    function addAndSelectUnit(unitId, unitName) {
        const nativeSelect = document.getElementById('approveUnitId');
        const exists = Array.from(nativeSelect.options).some(function(opt) {
            return String(opt.value) === String(unitId);
        });

        if (!exists) {
            const option = new Option(unitName, unitId, false, false);
            nativeSelect.add(option);
        }

        if (approveUnitSelect) {
            // Sync from the native select so the new option keeps list order.
            approveUnitSelect.sync();
            approveUnitSelect.setValue(String(unitId), true);
            approveUnitSelect.close();
        } else {
            nativeSelect.value = String(unitId);
        }
    }

    function submitCreateUnit() {
        const unitNameInput = document.getElementById('quickUnitName');
        const errorEl = document.getElementById('quickUnitError');
        const unitName = unitNameInput.value.trim();

        errorEl.classList.add('hidden');
        errorEl.textContent = '';

        if (!unitName) {
            errorEl.textContent = 'Unit name is required.';
            errorEl.classList.remove('hidden');
            return;
        }

        setCreateUnitLoading(true);

        fetch(quickCreateUnitUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ name: unitName })
        })
        .then(function(response) {
            return response.json().then(function(data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function(result) {
            if (!result.ok) {
                const validationMessage = result.data && result.data.errors && result.data.errors.name ? result.data.errors.name[0] : (result.data.message || 'Failed to create unit.');
                throw new Error(validationMessage);
            }

            const unitId = result.data.unit && result.data.unit.id ? result.data.unit.id : null;
            const unitNameResult = result.data.unit && result.data.unit.name ? result.data.unit.name : unitName;

            if (!unitId) {
                throw new Error('Unit created but response is missing the unit id.');
            }

            addAndSelectUnit(unitId, unitNameResult);
            closeCreateUnitModal();

            if (window.Toast) {
                Toast.fire({ icon: 'success', title: result.data.message || 'Unit created successfully!' });
            }
        })
        .catch(function(err) {
            errorEl.textContent = err.message || 'Failed to create unit.';
            errorEl.classList.remove('hidden');
        })
        .finally(function() {
            setCreateUnitLoading(false);
        });
    }

    document.getElementById('openCreateUnitModalBtn').addEventListener('click', openCreateUnitModal);
    document.getElementById('closeCreateUnitModalBtn').addEventListener('click', closeCreateUnitModal);
    document.getElementById('cancelCreateUnitBtn').addEventListener('click', closeCreateUnitModal);
    document.getElementById('saveCreateUnitBtn').addEventListener('click', submitCreateUnit);
    document.getElementById('quickUnitName').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitCreateUnit();
        }
    });

    document.getElementById('createUnitModal').addEventListener('click', function(e) {
        if (e.target.id === 'createUnitModal') {
            closeCreateUnitModal();
        }
    });
});

function openApproveModal(id) {
    document.getElementById('approveApplicationId').value = id;
    if (approveUnitSelect) {
        approveUnitSelect.clear(true);
    } else {
        document.getElementById('approveUnitId').value = '';
    }
    document.getElementById('approveDesignation').value = '';
    document.getElementById('approveModal').classList.remove('hidden');
    document.getElementById('approveModal').classList.add('flex');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.remove('flex');
    document.getElementById('approveModal').classList.add('hidden');
}

function approveApplication() {
    const id = document.getElementById('approveApplicationId').value;
    const unitId = document.getElementById('approveUnitId').value;
    const designation = document.getElementById('approveDesignation').value.trim();

    if (!unitId || !designation) {
        Toast.fire({ icon: 'error', title: 'Please select unit and enter designation' });
        return;
    }

    const approveUrl = approveUrlTemplate.replace('__ID__', id);

    fetch(approveUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ unit_id: unitId, designation: designation })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Toast.fire({ icon: 'success', title: data.message });
            $('#applicationsTable').DataTable().ajax.reload();
            closeApproveModal();
        } else {
            Toast.fire({ icon: 'error', title: data.message || 'Failed to approve' });
        }
    })
    .catch(error => {
        Toast.fire({ icon: 'error', title: 'Something went wrong!' });
    });
}

function deleteApplication(id) {
    Swal.fire({
        title: 'Delete Application',
        text: 'Are you sure you want to delete this application?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteUrl = deleteUrlTemplate.replace('__ID__', id);
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({ icon: 'success', title: data.message });
                    $('#applicationsTable').DataTable().ajax.reload();
                } else {
                    Toast.fire({ icon: 'error', title: data.message });
                }
            })
            .catch(error => {
                Toast.fire({ icon: 'error', title: 'Something went wrong!' });
            });
        }
    });
}
</script>
@endsection
