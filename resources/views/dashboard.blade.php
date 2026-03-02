@extends('layouts.master')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Welcome to Admin System</h1>
            <p class="text-gray-600">Manage users and system settings efficiently</p>
        </div>
        <div class="bg-gradient-to-r from-green-400 to-green-500 p-4 rounded-lg">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
            </svg>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- User Management -->
        <a href="/backend_vitonova/users" class="flex flex-col items-center p-6 border border-gray-200 rounded-lg group transition-all duration-200">
            <div class="rounded-lg mb-4 p-4 shadow-lg" style="background: linear-gradient(135deg, #3b82f6 60%, #06b6d4 100%); color: #fff;">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <span class="font-semibold">User Management</span>
            <span class="text-sm text-gray-500">Manage system users</span>
        </a>
        
        <!-- Settings -->
        <a href="#" class="flex flex-col items-center p-6 border border-gray-200 rounded-lg group transition-all duration-200">
            <div class="rounded-lg mb-4 p-4 shadow-lg" style="background: linear-gradient(135deg, #6366f1 60%, #3b82f6 100%); color: #fff;">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19.14,12.94a1,1,0,0,0-.26-1.09l-1.43-1.43a1,1,0,0,0-1.09-.26l-1.7.68a6.07,6.07,0,0,0-1.45-.85l-.26-1.81A1,1,0,0,0,12,6h-2a1,1,0,0,0-1,.88l-.26,1.81a6.07,6.07,0,0,0-1.45.85l-1.7-.68a1,1,0,0,0-1.09.26L4.12,11.85a1,1,0,0,0-.26,1.09l.68,1.7a6.07,6.07,0,0,0,.85,1.45l-1.81.26A1,1,0,0,0,4,18h2a1,1,0,0,0,1-.88l.26-1.81a6.07,6.07,0,0,0,1.45-.85l1.7.68a1,1,0,0,0,1.09-.26l1.43-1.43a1,1,0,0,0,.26-1.09Z"/>
                </svg>
            </div>
            <span class="font-semibold">Settings</span>
            <span class="text-sm text-gray-500">System configuration</span>
        </a>
        
        <!-- Reports -->
        <a href="#" class="flex flex-col items-center p-6 border border-gray-200 rounded-lg group transition-all duration-200">
            <div class="rounded-lg mb-4 p-4 shadow-lg" style="background: linear-gradient(135deg, #f59e42 60%, #f43f5e 100%); color: #fff;">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-2-8H7v2h10v-2zm0-4H7v2h10V7zm0 8H7v2h10v-2z"/>
                </svg>
            </div>
            <span class="font-semibold">Reports</span>
            <span class="text-sm text-gray-500">View system reports</span>
        </a>
    </div>
</div>

@endsection