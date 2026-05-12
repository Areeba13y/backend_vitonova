@extends('layouts.master')

@section('title', 'Create User')
@section('page_title', 'Create User')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Add New User</h2>
        <p class="text-sm text-gray-500 mt-1">Fill in the user details</p>
    </div>
    <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Users
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <form id="userForm" method="POST" class="space-y-4">
            @csrf
            
            <div class="flex items-center space-x-6 mb-6">
                <div class="shrink-0">
                    <div id="avatarPreview" class="relative cursor-pointer group">
                        <div class="w-24 h-24 rounded-full bg-gray-100 border-4 border-gray-200 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-green-400 group-hover:shadow-lg">
                            <i class="fas fa-user text-gray-300 text-3xl" id="placeholderIcon"></i>
                            <img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        </div>
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-black group-hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all duration-300">
                            <i class="fas fa-search text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-lg"></i>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                            <i class="fas fa-camera text-white text-xs"></i>
                        </div>
                    </div>
                    <label for="profile_picture" class="block mt-2">
                        <span class="text-xs text-green-600 hover:text-green-700 cursor-pointer font-medium">Change Photo</span>
                    </label>
                    <input id="profile_picture" type="file" name="profile_picture" accept="image/*" class="hidden">
                </div>
                <div class="text-sm text-gray-500">
                    <p>Upload a profile picture</p>
                    <p class="text-xs mt-1">JPG, PNG or GIF. Max 2MB.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input id="name" type="text" name="name" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <span class="text-red-500 text-xs hidden" id="name_error"></span>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input id="email" type="email" name="email" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <span class="text-red-500 text-xs hidden" id="email_error"></span>
                </div>

                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                    <div class="relative">
                        <select id="unit_id" name="unit_id" required
                                class="w-full px-3 py-2 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none bg-white">
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    <span class="text-red-500 text-xs hidden" id="unit_id_error"></span>
                </div>

                <div>
                    <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                    <input id="designation" type="text" name="designation"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                    <input id="contact" type="text" name="contact"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea id="address" name="address" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
            </div>

            <div>
                <label for="details" class="block text-sm font-medium text-gray-700 mb-1">Details</label>
                <textarea id="details" name="details" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                    <span id="submitText"><i class="fas fa-save mr-2"></i>Save User</span>
                    <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Lightbox Modal -->
<div id="imageLightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4" style="opacity: 0; transition: opacity 0.3s ease;">
    <button id="closeLightbox" class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10">
        <i class="fas fa-times text-2xl"></i>
    </button>
    <div class="flex items-center justify-center w-full h-full">
        <div class="relative max-w-4xl w-full flex items-center justify-center">
            <img id="lightboxImage" src="" alt="Preview" class="max-h-[80vh] w-auto rounded-lg shadow-2xl transform scale-95 opacity-0 transition-all duration-500" style="transform: scale(0.9);">
        </div>
    </div>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-sm bg-black bg-opacity-50 px-4 py-2 rounded-lg">
        Click anywhere to close
    </div>
</div>

<script>
$(document).ready(function() {
    let currentImageSrc = '';
    
    $('#profile_picture').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentImageSrc = e.target.result;
                $('#previewImage').attr('src', currentImageSrc).removeClass('hidden');
                $('#placeholderIcon').addClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#avatarPreview').click(function() {
        if (currentImageSrc) {
            $('#lightboxImage').attr('src', currentImageSrc);
            $('#imageLightbox').removeClass('hidden').css('opacity', '1');
            setTimeout(function() {
                $('#lightboxImage').css('transform', 'scale(1)').css('opacity', '1');
            }, 50);
        }
    });

    $('#closeLightbox, #imageLightbox').click(function(e) {
        if (e.target.id === 'closeLightbox' || e.target.id === 'imageLightbox') {
            $('#lightboxImage').css('transform', 'scale(0.9)').css('opacity', '0');
            setTimeout(function() {
                $('#imageLightbox').css('opacity', '0').addClass('hidden');
            }, 300);
        }
    });

    $(document).keydown(function(e) {
        if (e.key === 'Escape' && !$('#imageLightbox').hasClass('hidden')) {
            $('#lightboxImage').css('transform', 'scale(0.9)').css('opacity', '0');
            setTimeout(function() {
                $('#imageLightbox').css('opacity', '0').addClass('hidden');
            }, 300);
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
            url: '{{ route("users.store") }}',
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
                        window.location.href = response.redirect;
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
                        title: xhr.responseJSON.message || 'Error creating user'
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
});
</script>
@endsection
