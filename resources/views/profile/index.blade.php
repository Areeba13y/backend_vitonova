@extends('layouts.master')

@section('title', 'My Profile')
@section('page_title', 'My Profile')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-800">My Profile</h2>
    <p class="text-sm text-gray-500 mt-1">Manage your profile information and password</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>
            </div>
            <div class="p-6">
                <form id="profileForm" enctype="multipart/form-data" class="space-y-4">
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="shrink-0">
                            @if($user->profile_picture)
                            <img id="currentAvatar" src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                            @else
                            <div id="currentAvatar" class="w-24 h-24 rounded-full bg-indigo-500 flex items-center justify-center text-white text-3xl font-bold border-4 border-gray-200">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @endif
                        </div>
                        <div id="newAvatarPreview" class="shrink-0 hidden">
                            <img id="previewAvatar" src="" alt="Preview" class="w-24 h-24 rounded-full object-cover border-4 border-indigo-200">
                        </div>
                        <div>
                            <label for="profile_picture" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg cursor-pointer text-sm font-medium transition-colors">
                                <i class="fas fa-camera mr-2"></i>Change Photo
                            </label>
                            <input id="profile_picture" type="file" name="profile_picture" accept="image/*" class="hidden">
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF. Max 2MB.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input id="name" type="text" name="name" value="{{ $user->name }}" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Your full name">
                            <span class="text-red-500 text-xs hidden" id="name_error"></span>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input id="email" type="email" name="email" value="{{ $user->email }}" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="your@email.com">
                            <span class="text-red-500 text-xs hidden" id="email_error"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <input id="contact" type="text" name="contact" value="{{ $user->contact }}"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="+1 234 567 890">
                        </div>

                        <div>
                            <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                            <input id="designation" type="text" name="designation" value="{{ $user->designation }}"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Your job title">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="address" name="address" rows="2"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                                  placeholder="Your address">{{ $user->address }}</textarea>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" id="profileSubmitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium">
                            <span id="profileSubmitText"><i class="fas fa-save mr-2"></i>Save Changes</span>
                            <span id="profileSubmitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Change Password</h3>
            </div>
            <div class="p-6">
                <form id="passwordForm" class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                        <div class="relative">
                            <input id="current_password" type="password" name="current_password" required
                                   class="w-full px-3 py-2 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Enter current password">
                            <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-xs hidden" id="current_password_error"></span>
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                        <div class="relative">
                            <input id="new_password" type="password" name="new_password" required
                                   class="w-full px-3 py-2 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Enter new password">
                            <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-xs hidden" id="new_password_error"></span>
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password *</label>
                        <div class="relative">
                            <input id="new_password_confirmation" type="password" name="new_password_confirmation" required
                                   class="w-full px-3 py-2 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Confirm new password">
                            <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-xs hidden" id="new_password_confirmation_error"></span>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" id="passwordSubmitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium">
                            <span id="passwordSubmitText"><i class="fas fa-key mr-2"></i>Update Password</span>
                            <span id="passwordSubmitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm mt-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Account Info</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Role</p>
                    <p class="font-medium text-gray-800">{{ $user->role->name ?? 'N/A' }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Member Since</p>
                    <p class="font-medium text-gray-800">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                @if($user->unit)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Unit</p>
                    <p class="font-medium text-gray-800">{{ $user->unit->name }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

$(document).ready(function() {
    $('#profile_picture').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewAvatar').attr('src', e.target.result);
                $('#newAvatarPreview').removeClass('hidden');
                $('#currentAvatar').addClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#profileForm').submit(function(e) {
        e.preventDefault();
        
        $('#profileSubmitBtn').prop('disabled', true);
        $('#profileSubmitText').addClass('hidden');
        $('#profileSubmitLoading').removeClass('hidden');
        
        $('.text-red-500').addClass('hidden');
        $('input, textarea').removeClass('border-red-500');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("profile.update") }}',
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
                        window.location.reload();
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
                        title: xhr.responseJSON.message || 'Error updating profile'
                    });
                }
            },
            complete: function() {
                $('#profileSubmitBtn').prop('disabled', false);
                $('#profileSubmitText').removeClass('hidden');
                $('#profileSubmitLoading').addClass('hidden');
            }
        });
    });

    $('#passwordForm').submit(function(e) {
        e.preventDefault();
        
        $('#passwordSubmitBtn').prop('disabled', true);
        $('#passwordSubmitText').addClass('hidden');
        $('#passwordSubmitLoading').removeClass('hidden');
        
        $('.text-red-500').addClass('hidden');
        $('input').removeClass('border-red-500');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("profile.password") }}',
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
