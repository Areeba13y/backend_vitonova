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
        <form id="collaborationForm" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-2xl">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    @if($collaboration->logo)
                    <div id="currentLogo" class="mb-2">
                        <img src="{{ asset($collaboration->logo) }}" alt="{{ $collaboration->organization_name }}" class="w-20 h-20 object-contain rounded-lg border">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep current logo</p>
                    </div>
                    @endif
                    <div id="logoPreview" class="mt-2 hidden">
                        <img id="previewLogo" src="" alt="Preview" class="w-20 h-20 object-contain rounded-lg border">
                        <p class="text-xs text-gray-500 mt-1">New logo preview</p>
                    </div>
                    <input id="logo" type="file" name="logo" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <span class="text-red-500 text-xs hidden" id="logo_error"></span>
                </div>

                <div>
                    <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">Organization Name *</label>
                    <input id="organization_name" type="text" name="organization_name" value="{{ $collaboration->organization_name }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Organization name">
                    <span class="text-red-500 text-xs hidden" id="organization_name_error"></span>
                </div>
            </div>

            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                <input id="subtitle" type="text" name="subtitle" value="{{ $collaboration->subtitle }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Organization subtitle">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea id="description" name="description" rows="3" required
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                          placeholder="Partnership description">{{ $collaboration->description }}</textarea>
                <span class="text-red-500 text-xs hidden" id="description_error"></span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="representative_designation" class="block text-sm font-medium text-gray-700 mb-1">Representative Designation</label>
                    <input id="representative_designation" type="text" name="representative_designation" value="{{ $collaboration->representative_designation }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="e.g. Minister of Foreign Affairs">
                </div>

                <div>
                    <label for="representative_name" class="block text-sm font-medium text-gray-700 mb-1">Representative Name</label>
                    <input id="representative_name" type="text" name="representative_name" value="{{ $collaboration->representative_name }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="e.g. Dr. John Smith">
                </div>
            </div>

            <div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $collaboration->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Active (show on website)</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('collaborations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium">
                    <span id="submitText"><i class="fas fa-save mr-2"></i>Update Collaboration</span>
                    <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#logo').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewLogo').attr('src', e.target.result);
                $('#logoPreview').removeClass('hidden');
                $('#currentLogo').addClass('hidden');
            };
            reader.readAsDataURL(file);
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
