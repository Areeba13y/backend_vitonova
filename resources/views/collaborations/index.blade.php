@extends('layouts.master')

@section('title', 'Collaborations')
@section('page_title', 'Collaboration Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <h4 class="text-xl font-semibold text-gray-800">Collaborations</h4>
        <button onclick="openAddCollaborationModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">Add Collaboration</button>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtitle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Representative</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="collaborationsTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($collaborations as $item)
                        @php
                            $itemData = [
                                'id' => $item->id,
                                'logo' => $item->logo,
                                'logo_url' => asset($item->logo),
                                'organization_name' => $item->organization_name,
                                'subtitle' => $item->subtitle,
                                'description' => $item->description,
                                'representative_designation' => $item->representative_designation,
                                'representative_name' => $item->representative_name,
                                'is_active' => $item->is_active,
                            ];
                        @endphp
                        <tr id="collaboration-row-{{ $item->id }}" data-collaboration-id="{{ $item->id }}">
                            <td class="px-6 py-4"><img src="{{ asset($item->logo) }}" class="w-14 h-14 rounded object-cover border" alt="{{ $item->organization_name }}"></td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $item->organization_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->subtitle ?: '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ trim(($item->representative_designation ?: '') . ' ' . ($item->representative_name ?: '')) ?: '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button
                                    type="button"
                                    onclick="toggleCollaborationActive({{ $item->id }})"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $item->is_active ? 'bg-green-500' : 'bg-gray-300' }}"
                                    aria-label="Toggle active status"
                                >
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $item->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-3">
                                    <button onclick="editCollaboration(this)" data-collaboration="{{ json_encode($itemData, JSON_HEX_APOS | JSON_HEX_QUOT) }}" class="text-yellow-600">Edit</button>
                                    <button onclick="deleteCollaboration({{ $item->id }}, {{ json_encode($item->organization_name) }})" class="text-red-600">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-collaborations-row"><td colspan="6" class="px-6 py-10 text-center text-gray-500">No collaborations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($collaborations->hasPages())
            <div class="mt-6">{{ $collaborations->links() }}</div>
        @endif
    </div>
</div>

@include('components.collaboration-modal')

<script>
function toggleCollaborationActive(id) {
    fetch(`{{ route('collaborations.toggle-active', ':id') }}`.replace(':id', id), {
        method: 'PATCH',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(async (response) => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Update failed');
        return data;
    })
    .then((data) => {
        Toast.fire({ icon: 'success', title: data.message });
        window.location.reload();
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to update status.' });
    });
}
</script>
@endsection
