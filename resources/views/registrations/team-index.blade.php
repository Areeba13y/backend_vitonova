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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $app)
                        <tr id="row-{{ $app->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $app->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->position }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span id="status-{{ $app->id }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $app->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('team-applications.download', $app->id) }}" class="text-blue-600 hover:text-blue-900" title="Download Resume">
                                    Download
                                </a>
                                @if($app->status === 'pending')
                                    <button onclick="approveApplication({{ $app->id }})" id="btn-approve-{{ $app->id }}" class="text-green-600 hover:text-green-900">
                                        Approve
                                    </button>
                                @endif
                                <button onclick="deleteApplication({{ $app->id }})" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
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

<script>
function approveApplication(id) {
    if (!confirm('Are you sure you want to approve this application?')) return;

    fetch(`/team-applications/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Approved!', data.message, 'success');
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
}

function deleteApplication(id) {
    if (!confirm('Are you sure you want to remove this application?')) return;

    fetch(`/team-applications/${id}`, {
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
}
</script>
@endsection
