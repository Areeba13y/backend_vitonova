@extends('layouts.master')

@section('title', $collaboration->organization_name)
@section('page_title', 'Collaboration Details')

@section('content')
<div class="mb-4 sm:mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-handshake"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $collaboration->organization_name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Partnership details</p>
            </div>
        </div>
        <a href="{{ route('collaborations.index') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Collaborations
        </a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-4 mb-6">
                    @if($collaboration->logo)
                    <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ asset($collaboration->logo) }}" alt="{{ $collaboration->organization_name }}" class="w-full h-full object-contain">
                    </div>
                    @endif
                    <div class="flex-1 sm:ml-6">
                        @if($collaboration->subtitle)
                        <h3 class="text-lg font-medium text-gray-600 mb-2">{{ $collaboration->subtitle }}</h3>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $collaboration->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $collaboration->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                <p class="text-gray-600 mb-6 whitespace-pre-line">{{ $collaboration->description }}</p>
                
                @if($collaboration->representative_name || $collaboration->representative_designation)
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Representative</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-4">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $collaboration->representative_name }}</p>
                            @if($collaboration->representative_designation)
                            <p class="text-sm text-gray-500">{{ $collaboration->representative_designation }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Details</h3>
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <p class="font-medium {{ $collaboration->is_active ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $collaboration->is_active ? 'Active Partner' : 'Inactive' }}
                    </p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Created</p>
                    <p class="font-medium text-gray-800">{{ $collaboration->created_at->format('M d, Y') }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                    <p class="font-medium text-gray-800">{{ $collaboration->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
            
            <div class="mt-6 flex flex-col space-y-3">
                <a href="{{ route('collaborations.edit', $collaboration) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Collaboration
                </a>
                <button onclick="toggleCollaborationActive({{ $collaboration->id }})" class="inline-flex items-center justify-center px-4 py-2 {{ $collaboration->is_active ? 'bg-yellow-50 hover:bg-yellow-100 text-yellow-600' : 'bg-green-50 hover:bg-green-100 text-green-600' }} rounded-lg font-medium text-sm transition-colors">
                    <i class="fas {{ $collaboration->is_active ? 'fa-ban mr-2' : 'fa-check mr-2' }}"></i>
                    {{ $collaboration->is_active ? 'Deactivate' : 'Activate' }}
                </button>
                <button onclick="deleteCollaboration({{ $collaboration->id }}, '{{ addslashes($collaboration->organization_name) }}')" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-medium text-sm transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Collaboration
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCollaborationActive(id) {
    fetch(`${baseUrl}/collaborations/${id}/toggle-active`, {
        method: 'PATCH',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        Toast.fire({ icon: 'success', title: data.message });
        setTimeout(function() {
            window.location.reload();
        }, 1500);
    });
}

function deleteCollaboration(id, name) {
    Swal.fire({
        title: 'Delete Collaboration',
        text: 'Are you sure you want to delete "' + name + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${baseUrl}/collaborations/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Collaboration deleted successfully!' });
                    setTimeout(function() {
                        window.location.href = '{{ route("collaborations.index") }}';
                    }, 1500);
                }
            });
        }
    });
}
</script>
@endsection
