@extends('layouts.master')

@section('title', 'Users')
@section('page_title', 'User Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 border-b border-gray-200 gap-4">
        <h4 class="text-xl font-semibold text-gray-800">Users</h4>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Search Input -->
            <div class="relative">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="pl-10 pr-10 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent w-64 transition-shadow"
                    placeholder="Search by name, email, designation..."
                    value="{{ $search ?? '' }}"
                >
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                @if(!empty($search))
                <button type="button" id="clearSearch" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
                @endif
            </div>

            <select id="roleFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" data-code="{{ $role->code }}" {{ (string) $selectedRoleId === (string) $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <select id="unitFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Units</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ (string) ($selectedUnitId ?? '') === (string) $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }}
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
            <button onclick="openAddUserModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center whitespace-nowrap">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S.No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                    @foreach($users as $index => $user)
                        @include('users.partials.table-row', ['user' => $user, 'index' => $index])
                    @endforeach
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
    const unitFilter = document.getElementById('unitFilter');
    const eventFilterWrap = document.getElementById('eventFilterWrap');
    const eventFilter = document.getElementById('eventFilter');
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const usersTableBody = document.getElementById('usersTableBody');
    const usersPaginationWrap = document.getElementById('usersPaginationWrap');
    const usersEmptyState = document.getElementById('usersEmptyState');
    const usersIndexUrl = '{{ route("users.index") }}';

    // Debounce timer
    let searchTimeout;

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
        const selectedUnit = unitFilter.value;
        const searchQuery = searchInput.value.trim();

        if (searchQuery) {
            url.searchParams.set('search', searchQuery);
        } else {
            url.searchParams.delete('search');
        }

        if (selectedRole) {
            url.searchParams.set('role_id', selectedRole);
        } else {
            url.searchParams.delete('role_id');
        }

        if (selectedUnit) {
            url.searchParams.set('unit_id', selectedUnit);
        } else {
            url.searchParams.delete('unit_id');
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

    // Live search with debounce
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        // Show/hide clear button
        if (clearSearchBtn) {
            clearSearchBtn.style.display = query ? 'block' : 'none';
        }

        // Debounce: wait 300ms after typing stops
        searchTimeout = setTimeout(() => {
            fetchUsers(buildUsersUrl(usersIndexUrl));
        }, 300);
    });

    // Also trigger search on Enter key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            fetchUsers(buildUsersUrl(usersIndexUrl));
        }
        if (e.key === 'Escape') {
            searchInput.value = '';
            if (clearSearchBtn) clearSearchBtn.style.display = 'none';
            fetchUsers(buildUsersUrl(usersIndexUrl));
        }
    });

    // Clear search
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            searchInput.value = '';
            this.style.display = 'none';
            searchInput.focus();
            fetchUsers(buildUsersUrl(usersIndexUrl));
        });
    }

    roleFilter.addEventListener('change', function () {
        unitFilter.value = '';
        fetchUsers(buildUsersUrl(usersIndexUrl));
    });

    unitFilter.addEventListener('change', function () {
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
