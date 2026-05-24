@extends('layouts.master')

@section('title', 'Create Unit')
@section('page_title', 'Create Unit')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Add New Unit</h2>
                <p class="text-sm text-gray-500 mt-1">Create a new organizational unit</p>
            </div>
        </div>
        <a href="{{ route('units.index') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Units
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-4 sm:p-6">
        <form id="unitForm" method="POST" class="w-full">
            @csrf
            
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="flex-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Unit Name *</label>
                    <input id="name" type="text" name="name" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Enter unit name">
                    <span class="text-red-500 text-xs hidden" id="name_error"></span>
                </div>

                <div class="flex w-full lg:w-auto gap-3">
                    <a href="{{ route('units.index') }}" class="w-full lg:w-auto px-4 py-2 text-center bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="w-full lg:w-auto px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                        <span id="submitText"><i class="fas fa-save mr-2"></i>Save Unit</span>
                        <span id="submitLoading" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i>Saving...</span>
                    </button>
                </div>
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
            url: '{{ route("units.store") }}',
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
                        title: xhr.responseJSON.message || 'Error creating unit'
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
