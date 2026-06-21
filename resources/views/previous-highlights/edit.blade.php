{{-- resources/views/previous-highlights/edit.blade.php --}}
@extends('layouts.master')

@section('title', 'Edit Highlight')
@section('page_title', 'Edit Highlight')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-star-edit"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Edit Highlight</h2>
                <p class="text-sm text-gray-500 mt-1">Update highlight details</p>
            </div>
        </div>
        <a href="{{ route('previous-highlights.index') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Highlights
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-4 sm:p-6">
        <form id="highlightForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="flex-1">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input id="title" type="text" name="title" value="{{ $previousHighlight->title }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Highlight title">
                    <span class="text-red-500 text-xs hidden" id="title_error"></span>
                </div>

                <div class="flex-1">
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn Post URL</label>
                    <input id="url" type="url" name="url" value="{{ $previousHighlight->url }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="https://linkedin.com/posts/...">
                    <span class="text-red-500 text-xs hidden" id="url_error"></span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="w-full lg:w-64">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input id="image" type="file" name="image" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <span class="text-red-500 text-xs hidden" id="image_error"></span>
                </div>

                <div class="flex w-full lg:w-auto gap-3">
                    <a href="{{ route('previous-highlights.index') }}" class="w-full lg:w-auto px-4 py-2 text-center bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="w-full lg:w-auto px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                        <span id="submitText"><i class="fas fa-save mr-2"></i>Update</span>
                        <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <div class="w-full lg:w-64 space-y-4">
                    @if($previousHighlight->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                        <div id="currentImageContainer" class="relative cursor-pointer group rounded-lg overflow-hidden border-2 border-gray-200 hover:border-green-400 transition-all">
                            <img id="currentImageImg" src="{{ asset($previousHighlight->image) }}" alt="{{ $previousHighlight->title }}" class="w-full h-40 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all">
                                <i class="fas fa-search text-white opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-center">Click to preview</p>
                    </div>
                    @endif

                    <div id="imagePreviewContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Image Preview</label>
                        <div id="imagePreview" class="relative cursor-pointer group rounded-lg overflow-hidden border-2 border-green-400 hover:border-green-500 transition-all">
                            <img id="previewImage" src="" alt="Preview" class="w-full h-40 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all">
                                <i class="fas fa-search text-white opacity-0 group-hover:opacity-100 transition-opacity text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-green-600 mt-1 text-center">New image - click to preview</p>
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
    let currentImageSrc = '{{ $previousHighlight->image ? asset($previousHighlight->image) : "" }}';
    let newImageSrc = '';
    
    $('#image').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newImageSrc = e.target.result;
                $('#previewImage').attr('src', newImageSrc);
                $('#imagePreviewContainer').removeClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#currentImageContainer').click(function() {
        if (currentImageSrc) {
            $('#lightboxImage').attr('src', currentImageSrc);
            $('#imageLightbox').removeClass('hidden').css('opacity', '1');
            setTimeout(function() {
                $('#lightboxImage').css('transform', 'scale(1)').css('opacity', '1');
            }, 50);
        }
    });

    $('#imagePreview').click(function() {
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

    $('#highlightForm').submit(function(e) {
        e.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('hidden');
        $('#submitLoading').removeClass('hidden');
        
        $('.text-red-500').addClass('hidden');
        $('input').removeClass('border-red-500');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("previous-highlights.update", $previousHighlight->id) }}',
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
                        window.location.href = '{{ route("previous-highlights.index") }}';
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
                        title: xhr.responseJSON.message || 'Error updating highlight'
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