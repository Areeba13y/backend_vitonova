@extends('layouts.master')

@section('title', 'Edit Unit')
@section('page_title', 'Edit Unit')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Edit Unit</h2>
        <p class="text-sm text-gray-500 mt-1">Update unit details</p>
    </div>
    <a href="{{ route('units.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Units
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <form id="unitForm" method="POST" class="max-w-md">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Unit Name *</label>
                <input id="name" type="text" name="name" value="{{ $unit->name }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Enter unit name">
                <span class="text-red-500 text-xs hidden" id="name_error"></span>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('units.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium">
                    <span id="submitText"><i class="fas fa-save mr-2"></i>Update Unit</span>
                    <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#unitForm').submit(function(e) {
        e.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('hidden');
        $('#submitLoading').removeClass('hidden');
        $('#name_error').addClass('hidden');
        $('#name').removeClass('border-red-500');
        
        $.ajax({
            url: '{{ route("units.update", $unit) }}',
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
                    setTimeout(function() {
                        window.location.href = '{{ route("units.index") }}';
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.name) {
                        $('#name_error').text(errors.name[0]).removeClass('hidden');
                        $('#name').addClass('border-red-500');
                    }
                    Toast.fire({
                        icon: 'error',
                        title: 'Please fix the errors'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message || 'Error updating unit'
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
