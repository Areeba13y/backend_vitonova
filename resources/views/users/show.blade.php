@extends('layouts.master')

@section('title', 'User Details')
@section('page_title', 'User Details')

@section('content')
<div class="max-w-4xl mx-auto">
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
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-800">User Information</h4>
            <div class="flex space-x-2">
                <button onclick="editUser(this)" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center"
                        data-user="{{ $user->toJson(JSON_HEX_APOS | JSON_HEX_QUOT) }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit User
                </button>
                <button onclick="deleteUser({{ $user->id }}, {{ json_encode($user->name) }})" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete User
                </button>
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
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email Verified</label>
                            <span class="text-gray-900 font-medium">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Not Verified
                                    </span>
                                @endif
                            </span>
                        </div>

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
</div>

@include('components.user-modal')
@endsection
