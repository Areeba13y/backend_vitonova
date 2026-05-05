@extends('layouts.master')

@section('title', 'Contact Messages')
@section('page_title', 'Contact Messages')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <h4 class="text-xl font-semibold text-gray-800">Contact Messages</h4>
        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
            {{ $unreadUsersCount }} Unread
        </span>
    </div>

    <div class="p-6">
        <div class="mb-4 flex justify-end">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex items-center gap-2">
                <label for="status-filter" class="text-sm text-gray-600">Filter</label>
                <select
                    id="status-filter"
                    name="status"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()"
                >
                    <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>All Messages</option>
                    <option value="unread" {{ ($filter ?? 'all') === 'unread' ? 'selected' : '' }}>Unread Messages</option>
                    <option value="read" {{ ($filter ?? 'all') === 'read' ? 'selected' : '' }}>Read Messages</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S.No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($messages as $index => $msg)
                        @php
                            $userMessages = $msg->user?->contactMessages ?? collect();
                            $hasUnread = $userMessages->contains(fn ($item) => ! $item->is_read);
                        @endphp
                        <tr class="{{ $hasUnread ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $msg->user?->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $msg->user?->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($msg->message, 80) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($hasUnread)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Unread
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $msg->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors"
                                    onclick="openHistoryModal({{ $msg->user_id }})"
                                    title="View History"
                                    aria-label="View History"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-3-6.708"></path>
                                    </svg>
                                </button>
                                @if($hasUnread)
                                    <form action="{{ route('admin.contacts.markRead', $msg->user_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="Mark Read" aria-label="Mark Read">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.contacts.destroy', $msg->user_id) }}" method="POST" class="inline js-delete-contact-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" title="Delete" aria-label="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6M8 7l1 13h6l1-13"></path>
                                        </svg>
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="text-gray-400 mb-2">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                No messages yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach($messages as $msg)
            <div id="history-modal-{{ $msg->user_id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 px-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div>
                            <h5 class="text-lg font-semibold text-gray-800">Message History</h5>
                            <p class="text-sm text-gray-500">{{ $msg->user?->name ?? 'Guest' }} ({{ $msg->user?->email ?? 'N/A' }})</p>
                        </div>
                        <button type="button" class="text-gray-500 hover:text-gray-700 text-xl leading-none" onclick="closeHistoryModal({{ $msg->user_id }})">&times;</button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[60vh]">
                        @forelse(($msg->user?->contactMessages ?? collect()) as $historyMessage)
                            <div class="mb-4 pb-4 border-b border-gray-100 last:border-b-0">
                                <p class="text-xs font-semibold text-gray-500 mb-1">{{ $historyMessage->created_at?->format('d M Y, h:i A') }}</p>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $historyMessage->message }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No message history found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
    if (window.Toast && typeof window.Toast.fire === 'function') {
        window.Toast.fire({
            icon: 'success',
            title: @json(session('success'))
        });
    } else {
        console.log(@json(session('success')));
    }
    @endif

    document.querySelectorAll('.js-delete-contact-form').forEach((form) => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!window.Swal || typeof window.Swal.fire !== 'function') {
                if (confirm('Delete all messages for this user?')) {
                    form.submit();
                }
                return;
            }

            window.Swal.fire({
                title: 'Are you sure?',
                text: 'Delete all messages for this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

function openHistoryModal(id) {
    const modal = document.getElementById(`history-modal-${id}`);
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeHistoryModal(id) {
    const modal = document.getElementById(`history-modal-${id}`);
    if (!modal) return;
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>
@endsection
