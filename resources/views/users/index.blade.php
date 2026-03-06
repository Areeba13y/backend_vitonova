@extends('layouts.master')

@section('title', 'Users')
@section('page_title', 'User Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <h4 class="text-xl font-semibold text-gray-800">Users</h4>
        <div class="flex items-center gap-3">
            <select id="roleFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" data-code="{{ $role->code }}" {{ (string) $selectedRoleId === (string) $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <div id="eventFilterWrap" class="{{ !empty($showEventFilter) ? '' : 'hidden' }}">
                <select id="eventFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All Events</option>
                    @foreach($eventsForFilter as $eventOption)
                        <option value="{{ $eventOption->id }}" {{ (string) $selectedEventId === (string) $eventOption->id ? 'selected' : '' }}>
                            {{ $eventOption->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button onclick="openAddUserModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New User
            </button>
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                    @include('users.partials.table-rows', ['users' => $users])
                </tbody>
            </table>
        </div>

        <div id="usersEmptyState" class="text-center py-8 {{ $users->isEmpty() ? '' : 'hidden' }}">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No users</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
                <div class="mt-6">
                    <button onclick="openAddUserModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        New User
                    </button>
                </div>
            </div>

            <div id="usersPaginationWrap" class="mt-6 {{ $users->hasPages() ? '' : 'hidden' }}">
                {{ $users->links() }}
            </div>
    </div>
</div>

@include('components.user-modal')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleFilter = document.getElementById('roleFilter');
    const eventFilterWrap = document.getElementById('eventFilterWrap');
    const eventFilter = document.getElementById('eventFilter');
    const usersTableBody = document.getElementById('usersTableBody');
    const usersPaginationWrap = document.getElementById('usersPaginationWrap');
    const usersEmptyState = document.getElementById('usersEmptyState');
    const usersIndexUrl = '{{ route("users.index") }}';

    function setLoading() {
        usersTableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-10 text-center text-gray-500">Loading users...</td></tr>';
    }

    function applyResponse(data, requestUrl) {
        usersTableBody.innerHTML = data.rows_html;
        usersPaginationWrap.innerHTML = data.pagination_html || '';
        updateEventFilter(data.show_event_filter, data.events || [], data.selected_event_id);

        if (data.pagination_html) {
            usersPaginationWrap.classList.remove('hidden');
        } else {
            usersPaginationWrap.classList.add('hidden');
        }

        if (data.is_empty) {
            usersEmptyState.classList.remove('hidden');
        } else {
            usersEmptyState.classList.add('hidden');
        }

        window.history.replaceState({}, '', requestUrl);
    }

    function updateEventFilter(show, events, selectedEventId) {
        if (show) {
            eventFilterWrap.classList.remove('hidden');
            eventFilter.innerHTML = '<option value="">All Events</option>';
            events.forEach(function (eventItem) {
                const option = document.createElement('option');
                option.value = String(eventItem.id);
                option.textContent = eventItem.title;
                if (String(selectedEventId || '') === String(eventItem.id)) {
                    option.selected = true;
                }
                eventFilter.appendChild(option);
            });
        } else {
            eventFilterWrap.classList.add('hidden');
            eventFilter.value = '';
        }
    }

    function buildUsersUrl(baseHref) {
        const url = new URL(baseHref || usersIndexUrl, window.location.origin);
        const selectedRole = roleFilter.value;

        if (selectedRole) {
            url.searchParams.set('role_id', selectedRole);
        } else {
            url.searchParams.delete('role_id');
        }

        if (!eventFilterWrap.classList.contains('hidden') && eventFilter.value) {
            url.searchParams.set('event_id', eventFilter.value);
        } else {
            url.searchParams.delete('event_id');
        }

        return url.toString();
    }

    function fetchUsers(url) {
        setLoading();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(async response => {
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to load users.');
                }
                return data;
            })
            .then(data => applyResponse(data, url))
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Load Failed',
                    text: 'Unable to load filtered users. Please try again.'
                });
            });
    }

    roleFilter.addEventListener('change', function () {
        eventFilter.value = '';
        fetchUsers(buildUsersUrl(usersIndexUrl));
    });

    eventFilter.addEventListener('change', function () {
        fetchUsers(buildUsersUrl(usersIndexUrl));
    });

    usersPaginationWrap.addEventListener('click', function (event) {
        const link = event.target.closest('a');
        if (!link) {
            return;
        }

        event.preventDefault();
        fetchUsers(buildUsersUrl(link.href));
    });
});
</script>
@endsection
