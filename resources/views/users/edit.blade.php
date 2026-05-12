@extends('layouts.master')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Edit User</h2>
        <p class="text-sm text-gray-500 mt-1">Update user details</p>
    </div>
    <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Users
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm mb-6">
    <div class="p-6">
        <form id="userForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="flex items-center mb-6">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-xl object-cover mr-4">
                @else
                    <div class="w-20 h-20 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold mr-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input id="name" type="text" name="name" value="{{ $user->name }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-red-500 text-xs hidden" id="name_error"></span>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input id="email" type="email" name="email" value="{{ $user->email }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-red-500 text-xs hidden" id="email_error"></span>
                </div>

                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                    <select id="role_id" name="role_id" required
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <select id="unit_id" name="unit_id"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $user->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                    <input id="designation" type="text" name="designation" value="{{ $user->designation }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                    <input id="contact" type="text" name="contact" value="{{ $user->contact }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea id="address" name="address" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ $user->address }}</textarea>
            </div>

            <div>
                <label for="details" class="block text-sm font-medium text-gray-700 mb-1">Details</label>
                <textarea id="details" name="details" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ $user->details }}</textarea>
            </div>

            <div>
                <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                <input id="profile_picture" type="file" name="profile_picture" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <div id="profilePreview" class="mt-2 hidden">
                    <img id="previewImage" src="" alt="Preview" class="w-20 h-20 rounded-lg object-cover">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium">
                    <span id="submitText"><i class="fas fa-save mr-2"></i>Update User</span>
                    <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-key mr-2 text-indigo-500"></i>Change Password</h3>
        <form id="passwordForm" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input id="current_password" type="password" name="current_password"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-red-500 text-xs hidden" id="current_password_error"></span>
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input id="new_password" type="password" name="new_password"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-red-500 text-xs hidden" id="new_password_error"></span>
                </div>
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input id="new_password_confirmation" type="password" name="new_password_confirmation"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" id="passwordSubmitBtn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                    <span id="passwordSubmitText"><i class="fas fa-key mr-2"></i>Update Password</span>
                    <span id="passwordSubmitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#profile_picture').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#profilePreview').removeClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#userForm').submit(function(e) {
        e.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('hidden');
        $('#submitLoading').removeClass('hidden');
        
        $('.text-red-500').addClass('hidden').text('');
        $('input, select, textarea').removeClass('border-red-500');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("users.update", $user) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    setTimeout(function() {
                        window.location.href = '{{ route("users.index") }}';
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '_error').text(value[0]).removeClass('hidden');
                        $('#' + key).addClass('border-red-500');
                    });
                    Toast.fire({
                        icon: 'error',
                        title: 'Please fix the errors'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message || 'Error updating user'
                    });
                }
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false);
                $('#submitText').removeClass('hidden');
                $('#submitLoading').addClass('hidden');
            }
        });
    });

    $('#passwordForm').submit(function(e) {
        e.preventDefault();
        
        $('#passwordSubmitBtn').prop('disabled', true);
        $('#passwordSubmitText').addClass('hidden');
        $('#passwordSubmitLoading').removeClass('hidden');
        
        $('#current_password_error, #new_password_error').addClass('hidden').text('');
        $('#current_password, #new_password').removeClass('border-red-500');
        
        $.ajax({
            url: '{{ route("users.update-password", $user) }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    $('#passwordForm')[0].reset();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '_error').text(value[0]).removeClass('hidden');
                        $('#' + key).addClass('border-red-500');
                    });
                    Toast.fire({
                        icon: 'error',
                        title: 'Please fix the errors'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message || 'Error updating password'
                    });
                }
            },
            complete: function() {
                $('#passwordSubmitBtn').prop('disabled', false);
                $('#passwordSubmitText').removeClass('hidden');
                $('#passwordSubmitLoading').addClass('hidden');
            }
        });
    });
});
</script>
@endsection
