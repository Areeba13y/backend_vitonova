{{-- resources/views/previous-highlights/show.blade.php --}}
@extends('layouts.master')

@section('title', $previousHighlight->title)
@section('page_title', 'Highlight Details')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $previousHighlight->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Highlight details</p>
            </div>
        </div>
        <a href="{{ route('previous-highlights.index') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Highlights
        </a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($previousHighlight->image)
            <div class="h-96 overflow-hidden">
                <img src="{{ asset($previousHighlight->image) }}" alt="{{ $previousHighlight->title }}" class="w-full h-full object-cover">
            </div>
            @endif
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Previous Highlight</span>
                    <span class="text-sm text-gray-500">Created {{ $previousHighlight->created_at->format('M d, Y') }}</span>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Title</h3>
                <p class="text-gray-600 mb-6">{{ $previousHighlight->title }}</p>

                @if($previousHighlight->url)
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">LinkedIn Post</h3>
                    <a href="{{ $previousHighlight->url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg font-medium text-sm transition-colors">
                        <i class="fab fa-linkedin mr-2"></i>
                        View LinkedIn Post
                        <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            
            <div class="space-y-3">
                @if($previousHighlight->url)
                <a href="{{ $previousHighlight->url }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fab fa-linkedin mr-2"></i>
                    View Post
                </a>
                @endif
                
                <a href="{{ route('previous-highlights.edit', $previousHighlight) }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Highlight
                </a>
                
                <button onclick="deleteHighlight({{ $previousHighlight->id }}, '{{ addslashes($previousHighlight->title) }}')" class="inline-flex items-center justify-center w-full px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Highlight
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteHighlight(id, title) {
    Swal.fire({
        title: 'Delete Highlight',
        text: 'Are you sure you want to delete "' + title + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("previous-highlights.index") }}/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({ 
                        icon: 'success', 
                        title: data.message || 'Highlight deleted successfully!' 
                    });
                    setTimeout(function() {
                        window.location.href = '{{ route("previous-highlights.index") }}';
                    }, 1500);
                } else {
                    Toast.fire({ 
                        icon: 'error', 
                        title: data.message || 'Error deleting highlight' 
                    });
                }
            })
            .catch(error => {
                Toast.fire({ 
                    icon: 'error', 
                    title: 'Error deleting highlight' 
                });
                console.error('Delete error:', error);
            });
        }
    });
}
</script>
@endsection