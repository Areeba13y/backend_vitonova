@extends('layouts.master')

@section('title', 'User Details')
@section('page_title', 'User Details')

@section('content')
<div class="w-full">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
        </a>
    </div>

    <!-- User Details Card -->
    <div class="bg-white rounded-lg shadow-md w-full">
        <!-- Profile Picture Section -->
        <div class="flex flex-col p-4">
            <div class="relative">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-green-400 shadow cursor-pointer profile-pic-clickable transition-transform duration-200 hover:scale-105">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center text-5xl font-bold text-gray-600 border-4 border-gray-200 shadow">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
           
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h5 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Basic Information</h5>
                    
                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">ID</label>
                            <span class="text-gray-900 font-medium">{{ $user->id }}</span>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Name</label>
                            <span class="text-gray-900 font-medium">{{ $user->name }}</span>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email</label>
                            <span class="text-gray-900 font-medium">{{ $user->email }}</span>
                        </div>

                        @if($user->designation)
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Designation</label>
                            <span class="text-gray-900 font-medium">{{ $user->designation }}</span>
                        </div>
                        @endif

                        @if($user->contact)
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Contact</label>
                            <span class="text-gray-900 font-medium">{{ $user->contact }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-4">
                    <h5 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Additional Information</h5>
                    
                    <div class="space-y-3">
                        @if($user->address)
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Address</label>
                            <span class="text-gray-900 font-medium">{{ $user->address }}</span>
                        </div>
                        @endif

                        

                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Account Status</label>
                            <span class="text-gray-900 font-medium">
                                @if($user->deleted_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Deleted
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h5 class="text-lg font-medium text-gray-900 mb-4">Timestamps</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Created At</label>
                        <span class="text-gray-900 font-medium">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</span>
                        <span class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Last Updated</label>
                        <span class="text-gray-900 font-medium">{{ $user->updated_at->format('F j, Y \a\t g:i A') }}</span>
                        <span class="text-sm text-gray-500">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div id="profilePicModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
        <div class="relative">
            <img id="profilePicModalImg" src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : '' }}" alt="Profile Picture" class="max-w-xs md:max-w-lg rounded-2xl shadow-2xl transform scale-75 opacity-0 transition-all duration-300">
            <button onclick="closeProfilePicModal()" class="absolute top-2 right-2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-1 shadow">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
    // Profile Picture Modal Logic
    function openProfilePicModal() {
        const modal = document.getElementById('profilePicModal');
        const img = document.getElementById('profilePicModalImg');
        modal.classList.remove('hidden');
        setTimeout(() => {
            img.classList.remove('scale-75', 'opacity-0');
            img.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    function closeProfilePicModal() {
        const modal = document.getElementById('profilePicModal');
        const img = document.getElementById('profilePicModalImg');
        img.classList.remove('scale-100', 'opacity-100');
        img.classList.add('scale-75', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    document.addEventListener('DOMContentLoaded', function() {
        const profilePic = document.querySelector('.profile-pic-clickable');
        if (profilePic) {
            profilePic.addEventListener('click', openProfilePicModal);
        }
    });
    </script>

@include('components.user-modal')
@endsection
