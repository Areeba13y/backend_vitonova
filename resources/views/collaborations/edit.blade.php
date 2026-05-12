@extends('layouts.master')

@section('title', 'Edit Collaboration')
@section('page_title', 'Edit Collaboration')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Edit Collaboration</h2>
        <p class="text-sm text-gray-500 mt-1">Update partnership details</p>
    </div>
    <a href="{{ route('collaborations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Collaborations
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <form id="collaborationForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">Organization Name *</label>
                    <input id="organization_name" type="text" name="organization_name" value="{{ $collaboration->organization_name }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Organization name">
                    <span class="text-red-500 text-xs hidden" id="organization_name_error"></span>
                </div>

                <div class="w-64">
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    <input id="logo" type="file" name="logo" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <span class="text-red-500 text-xs hidden" id="logo_error"></span>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('collaborations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                        <span id="submitText"><i class="fas fa-save mr-2"></i>Update</span>
                        <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                    </button>
                </div>
            </div>

            <div class="flex gap-6">
                <div class="flex-1 space-y-4">
                    <div>
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input id="subtitle" type="text" name="subtitle" value="{{ $collaboration->subtitle }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Organization subtitle">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea id="description" name="description" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                                  placeholder="Partnership description">{{ $collaboration->description }}</textarea>
                        <span class="text-red-500 text-xs hidden" id="description_error"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="representative_designation" class="block text-sm font-medium text-gray-700 mb-1">Representative Designation</label>
                            <input id="representative_designation" type="text" name="representative_designation" value="{{ $collaboration->representative_designation }}"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g. Minister of Foreign Affairs">
                        </div>

                        <div>
                            <label for="representative_name" class="block text-sm font-medium text-gray-700 mb-1">Representative Name</label>
                            <input id="representative_name" type="text" name="representative_name" value="{{ $collaboration->representative_name }}"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g. Dr. John Smith">
                        </div>
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $collaboration->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                            <span class="text-sm text-gray-700">Active (show on website)</span>
                        </label>
                    </div>
                </div>

                <div class="w-64 space-y-4">
                    @if($collaboration->logo)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Logo</label>
                        <div id="currentLogoContainer" class="relative cursor-pointer group rounded-lg overflow-hidden border-2 border-gray-200 hover:border-green-400 transition-all">
                            <img id="currentLogoImg" src="{{ asset($collaboration->logo) }}" alt="{{ $collaboration->organization_name }}" class="w-full h-48 object-contain bg-gray-50">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all">
                                <i class="fas fa-search text-white opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-center">Click to preview</p>
                    </div>
                    @endif

                    <div id="logoPreviewContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Logo Preview</label>
                        <div id="logoPreview" class="relative cursor-pointer group rounded-lg overflow-hidden border-2 border-green-400 hover:border-green-500 transition-all">
                            <img id="previewLogo" src="" alt="Preview" class="w-full h-48 object-contain bg-gray-50">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all">
                                <i class="fas fa-search text-white opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-green-600 mt-1 text-center">New logo - click to preview</p>
                    </div>
                </div>
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
    let currentImageSrc = '{{ $collaboration->logo ? asset($collaboration->logo) : "" }}';
    let newImageSrc = '';
    
    $('#logo').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newImageSrc = e.target.result;
                $('#previewLogo').attr('src', newImageSrc);
                $('#logoPreviewContainer').removeClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#currentLogoContainer').click(function() {
        if (currentImageSrc) {
            $('#lightboxImage').attr('src', currentImageSrc);
            $('#imageLightbox').removeClass('hidden').css('opacity', '1');
            setTimeout(function() {
                $('#lightboxImage').css('transform', 'scale(1)').css('opacity', '1');
            }, 50);
        }
    });

    $('#logoPreview').click(function() {
        if (newImageSrc) {
            $('#lightboxImage').attr('src', newImageSrc);
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

    $('#collaborationForm').submit(function(e) {
        e.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('hidden');
        $('#submitLoading').removeClass('hidden');
        
        $('.text-red-500').addClass('hidden');
        $('input, textarea').removeClass('border-red-500');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("collaborations.update", $collaboration) }}',
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
                        window.location.href = '{{ route("collaborations.index") }}';
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
                        title: xhr.responseJSON.message || 'Error updating collaboration'
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
