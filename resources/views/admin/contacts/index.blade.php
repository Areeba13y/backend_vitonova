@extends('layouts.master')

@section('title', 'Contact Messages')
@section('page_title', 'Messages')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Contact Messages</h2>
        <p class="text-sm text-gray-500 mt-1">Manage contact messages</p>
    </div>
    @if($unreadUsersCount > 0)
    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
        {{ $unreadUsersCount }} Unread
    </span>
    @endif
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 relative">
        <div id="messagesTableLoading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg" style="display: none;">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading data...</span>
            </div>
        </div>
        <table id="messagesTable" class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Name</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Email</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Message</th>
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

<div id="historyModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[80vh] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Message History</h3>
                <p id="historyUserInfo" class="text-sm text-gray-500"></p>
            </div>
            <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div id="historyContent" class="p-6 overflow-y-auto max-h-[60vh]">
            <div id="historyLoader" class="flex items-center justify-center py-8">
                <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading messages...</span>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#messagesTable').DataTable({
        serverSide: true,
        ajax: {
            url: @json(route('admin.contacts.datatable')),
            type: 'GET',
            beforeSend: function() {
                $('#messagesTableLoading').show();
            },
            complete: function() {
                $('#messagesTableLoading').hide();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
                $('#messagesTableLoading').hide();
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'message', name: 'message', orderable: false },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'date', name: 'date', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex-1"f>>rtip',
        language: {
            search: "",
            searchPlaceholder: "Search messages...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ messages",
            emptyTable: '<div class="text-center py-8"><i class="fas fa-envelope text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No messages found</p></div>',
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

function openHistoryModal(userId, userName, userEmail) {
    // Close all hamburger menus
    $('.actions-menu').removeClass('active');
    $('.actions-dropdown').hide();
    $('.actions-menu .hamburger input').prop('checked', false);
    
    $('#historyUserInfo').text(userName + ' (' + userEmail + ')');
    $('#historyContent').html(`
        <div class="flex items-center justify-center py-8">
            <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl mr-3"></i>
            <span class="text-gray-600">Loading messages...</span>
        </div>
    `);
    
    $('#historyModal').removeClass('hidden').addClass('flex');
    
    $.ajax({
        url: `${baseUrl}/admin/contacts/user/${userId}/messages`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let html = '';
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    html += `<div class="mb-4 pb-4 border-b border-gray-100 last:border-b-0">
                        <p class="text-xs font-semibold text-gray-500 mb-1">${msg.formatted_date}</p>
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">${msg.message}</p>
                    </div>`;
                });
            } else {
                html = '<p class="text-sm text-gray-500 text-center py-8">No message history found.</p>';
            }
            $('#historyContent').html(html);
        },
        error: function() {
            $('#historyContent').html('<p class="text-sm text-red-500 text-center py-8">Failed to load messages.</p>');
        }
    });
}

function closeHistoryModal() {
    $('#historyModal').removeClass('flex').addClass('hidden');
}

function markAsRead(userId) {
    // Close hamburger menu
    $('.actions-menu').removeClass('active');
    $('.actions-dropdown').hide();
    $('.actions-menu .hamburger input').prop('checked', false);
    
    $.ajax({
        url: `${baseUrl}/admin/contacts/user/${userId}/read`,
        type: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if (data.success) {
                Toast.fire({ icon: 'success', title: 'Marked as read' });
                $('#messagesTable').DataTable().ajax.reload();
            }
        }
    });
}

function deleteMessages(userId) {
    // Close hamburger menu
    $('.actions-menu').removeClass('active');
    $('.actions-dropdown').hide();
    $('.actions-menu .hamburger input').prop('checked', false);
    
    Swal.fire({
        title: 'Delete Messages',
        text: 'Are you sure you want to delete all messages for this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${baseUrl}/admin/contacts/user/${userId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        Toast.fire({ icon: 'success', title: data.message });
                        $('#messagesTable').DataTable().ajax.reload();
                    } else {
                        Toast.fire({ icon: 'error', title: data.message });
                    }
                }
            });
        }
    });
}
</script>
@endsection
