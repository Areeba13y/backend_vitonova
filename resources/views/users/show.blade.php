@extends('layouts.master')

@section('title', 'View User')
@section('page_title', 'View User')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">User Details</h2>
                <p class="text-sm text-gray-500 mt-1">View user information</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('users.edit', $user) }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('users.index') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6 mb-6">
            @if($user->profile_picture)
                <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-xl object-cover">
            @else
                <div class="w-24 h-24 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-3xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h3>
                <p class="text-gray-500">{{ $user->designation ?? 'No designation' }}</p>
                <div class="mt-2">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                        {{ $user->unit?->name ?? 'No unit' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                <p class="text-gray-800">{{ $user->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Contact</label>
                <p class="text-gray-800">{{ $user->contact ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Unit</label>
                <p class="text-gray-800">{{ $user->unit?->name ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Designation</label>
                <p class="text-gray-800">{{ $user->designation ?? '-' }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                <p class="text-gray-800">{{ $user->address ?? '-' }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Details</label>
                <p class="text-gray-800">{{ $user->details ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                <p class="text-gray-800">{{ $user->created_at->format('d M Y, h:i A') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Updated At</label>
                <p class="text-gray-800">{{ $user->updated_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end">
            <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')" class="w-full sm:w-auto px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Delete User
            </button>
        </div>
    </div>
</div>

<script>
function deleteUser(id, name) {
    Swal.fire({
        title: 'Delete User',
        text: 'Are you sure you want to delete "' + name + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${baseUrl}/users/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({ icon: 'success', title: 'User deleted successfully!' });
                        window.location.href = `${baseUrl}/users`;
                    }
                }
            });
        }
    });
}
</script>
@endsection
