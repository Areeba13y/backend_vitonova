@extends('layouts.master')

@section('title', 'Team Applications')
@section('page_title', 'Team Applications')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Applications</h2>
        <p class="text-sm text-gray-500 mt-1">Manage team applications</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 relative">
        <div id="applicationsTableLoading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg" style="display: none;">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-green-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading data...</span>
            </div>
        </div>
        <table id="applicationsTable" class="w-full">
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

<div id="approveModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative w-full max-w-md mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Approve Application</h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <input type="hidden" id="approveApplicationId">
                <div class="mb-4">
                    <label for="approveUnitId" class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                    <select id="approveUnitId" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
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
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
                    <button type="button" onclick="approveApplication()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-medium">Approve</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const approveUrlTemplate = @json(route('team-applications.approve', ['id' => '__ID__']));
const deleteUrlTemplate = @json(route('team-applications.destroy', ['id' => '__ID__']));

$(document).ready(function() {
    $('#applicationsTable').DataTable({
        serverSide: true,
        ajax: {
            url: @json(route('team-applications.datatable')),
            type: 'GET',
            beforeSend: function() {
                $('#applicationsTableLoading').show();
            },
            complete: function() {
                $('#applicationsTableLoading').hide();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
                $('#applicationsTableLoading').hide();
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
        dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex-1"f>>rtip',
        language: {
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
});

function openApproveModal(id) {
    document.getElementById('approveApplicationId').value = id;
    document.getElementById('approveUnitId').value = '';
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
