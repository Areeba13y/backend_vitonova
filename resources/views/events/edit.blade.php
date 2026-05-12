@extends('layouts.master')

@section('title', 'Edit Event')
@section('page_title', 'Edit Event')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Edit Event</h2>
        <p class="text-sm text-gray-500 mt-1">Update event details</p>
    </div>
    <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Events
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <form id="eventForm" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-2xl">
            @csrf
            @method('PUT')
            
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Event Image</label>
                @if($event->image)
                <div id="currentImage" class="mb-2">
                    <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                </div>
                @endif
                <div id="imagePreview" class="mb-2 hidden">
                    <img id="previewImage" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">New image preview</p>
                </div>
                <input id="image" type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <span class="text-red-500 text-xs hidden" id="image_error"></span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <input id="category" type="text" name="category" value="{{ $event->category }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="e.g., Workshop, Seminar">
                    <span class="text-red-500 text-xs hidden" id="category_error"></span>
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input id="title" type="text" name="title" value="{{ $event->title }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Event title">
                    <span class="text-red-500 text-xs hidden" id="title_error"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="submission_deadline" class="block text-sm font-medium text-gray-700 mb-1">Submission Deadline *</label>
                    <input id="submission_deadline" type="date" name="submission_deadline" value="{{ $event->submission_deadline?->format('Y-m-d') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-red-500 text-xs hidden" id="submission_deadline_error"></span>
                </div>

                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date *</label>
                    <input id="event_date" type="date" name="event_date" value="{{ $event->event_date?->format('Y-m-d') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-red-500 text-xs hidden" id="event_date_error"></span>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                          placeholder="Event description">{{ $event->description }}</textarea>
                <span class="text-red-500 text-xs hidden" id="description_error"></span>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium">
                    <span id="submitText"><i class="fas fa-save mr-2"></i>Update Event</span>
                    <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#image').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#imagePreview').removeClass('hidden');
                $('#currentImage').addClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#eventForm').submit(function(e) {
        e.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('hidden');
        $('#submitLoading').removeClass('hidden');
        
        $('.text-red-500').addClass('hidden');
        $('input, textarea').removeClass('border-red-500');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("events.update", $event) }}',
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
                        window.location.href = '{{ route("events.index") }}';
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
                        title: xhr.responseJSON.message || 'Error updating event'
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
