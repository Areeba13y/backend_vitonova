@extends('layouts.master')

@section('title', 'Contact Messages')
@section('page_title', 'Messages')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Contact Messages</h2>
                <p class="text-sm text-gray-500 mt-1">Manage contact messages</p>
            </div>
        </div>
        @if($unreadUsersCount > 0)
        <span class="inline-flex px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full self-start sm:self-auto">
            {{ $unreadUsersCount }} Unread
        </span>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-3 sm:p-4 lg:p-6 relative">
        <div class="overflow-x-auto lg:overflow-visible">
        <table id="messagesTable" class="w-full min-w-[980px]">
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
</div>

<div id="historyModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl mx-3 sm:mx-4 max-h-[80vh] overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Message History</h3>
                <p id="historyUserInfo" class="text-sm text-gray-500"></p>
            </div>
            <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div id="historyContent" class="p-4 sm:p-6 overflow-y-auto max-h-[60vh]">
            <div id="historyLoader" class="flex items-center justify-center py-8">
                <i class="fas fa-spinner fa-spin text-green-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Loading messages...</span>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function applyMessagesTableResponsiveClasses() {
        const wrapper = $('#messagesTable_wrapper');
        if (!wrapper.length) {
            return;
        }

        wrapper.find('> .messages-table-toolbar').addClass('flex flex-col lg:flex-row lg:items-center gap-3');
        wrapper.find('.messages-length').addClass('w-full');
        wrapper.find('.messages-filter').addClass('w-full lg:w-auto lg:ml-auto');
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
        wrapper.find('.messages-table-footer').addClass('flex flex-col gap-3 md:flex-row md:items-center md:justify-between');
        wrapper.find('.dataTables_info').addClass('!float-none text-center md:text-left !text-sm !text-gray-500');
        wrapper.find('.dataTables_paginate').addClass('!float-none flex justify-center md:justify-end');
    }

    $('#messagesTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: @json(route('admin.contacts.datatable')),
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr.responseText);
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
        autoWidth: false,
        dom: '<"messages-table-toolbar mb-4"<"messages-length"l><"messages-filter"f>>rt<"messages-table-footer mt-4"ip>',
        initComplete: function() {
            applyMessagesTableResponsiveClasses();
        },
        drawCallback: function() {
            applyMessagesTableResponsiveClasses();
        },
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-green-500 text-lg mr-2"></i> Loading data...</div>',
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
            <i class="fas fa-spinner fa-spin text-green-500 text-2xl mr-3"></i>
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
