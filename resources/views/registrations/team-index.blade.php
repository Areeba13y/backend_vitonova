@extends('layouts.master')

@section('title', 'Team Applications')
@section('page_title', 'Team Applications')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <h4 class="text-xl font-semibold text-gray-800">Applications</h4>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S.No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $index => $app)
                        <tr id="row-{{ $app->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $app->user?->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->user?->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->position }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span id="status-{{ $app->id }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $app->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                <a href="{{ route('team-applications.download', $app->id) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" title="Download Resume" aria-label="Download Resume">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0 0l-4-4m4 4l4-4"></path>
                                    </svg>
                                </a>
                              
                                @if($app->status === 'pending')
                                    <button onclick="openApproveModal({{ $app->id }})" id="btn-approve-{{ $app->id }}" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="Approve" aria-label="Approve">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @endif
                                <button onclick="deleteApplication({{ $app->id }})" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" title="Delete" aria-label="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6M8 7l1 13h6l1-13"></path>
                                    </svg>
                                </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    </div>
</div>

<div id="approveModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 px-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <h5 class="text-lg font-semibold text-gray-800 mb-4">Approve Application</h5>
        <input type="hidden" id="approveApplicationId">
        <div class="mb-4">
            <label for="approveUnitId" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
            <select id="approveUnitId" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Select Unit</option>
                @foreach(($units ?? []) as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label for="approveDesignation" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
            <input id="approveDesignation" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter designation">
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeApproveModal()" class="px-3 py-2 bg-gray-200 rounded-md">Cancel</button>
            <button type="button" onclick="approveApplication()" class="px-3 py-2 bg-green-600 text-white rounded-md">Approve</button>
        </div>
    </div>
</div>

<script>
const approveUrlTemplate = @json(route('team-applications.approve', ['id' => '__ID__']));
const deleteUrlTemplate = @json(route('team-applications.destroy', ['id' => '__ID__']));

function openApproveModal(id) {
    document.getElementById('approveApplicationId').value = id;
    document.getElementById('approveUnitId').value = '';
    document.getElementById('approveDesignation').value = '';
    const modal = document.getElementById('approveModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function approveApplication() {
    const id = document.getElementById('approveApplicationId').value;
    const unitId = document.getElementById('approveUnitId').value;
    const designation = document.getElementById('approveDesignation').value.trim();

    if (!unitId || !designation) {
        Swal.fire('Validation Error', 'Please select unit and enter designation.', 'error');
        return;
    }

    const runApprove = () => {
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
                Swal.fire('Approved!', data.message, 'success');
                closeApproveModal();
                document.getElementById(`status-${id}`).textContent = 'Approved';
                document.getElementById(`status-${id}`).className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                const btn = document.getElementById(`btn-approve-${id}`);
                if (btn) btn.remove();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Something went wrong!', 'error');
        });
    };

    if (!window.Swal || typeof window.Swal.fire !== 'function') {
        if (confirm('Are you sure you want to approve this application?')) {
            runApprove();
        }
        return;
    }

    window.Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to approve this application?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, approve',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            runApprove();
        }
    });
}

function deleteApplication(id) {
    const runDelete = () => {
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
                Swal.fire('Deleted!', data.message, 'success');
                document.getElementById(`row-${id}`).remove();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Something went wrong!', 'error');
        });
    };

    if (!window.Swal || typeof window.Swal.fire !== 'function') {
        if (confirm('Are you sure you want to remove this application?')) {
            runDelete();
        }
        return;
    }

    window.Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this application?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            runDelete();
        }
    });
}
</script>
@endsection
