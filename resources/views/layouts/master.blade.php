<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    @include('layouts.sidebar')
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen ml-64">
        <!-- Top Bar (optional, for mobile menu toggle and page title) -->
        <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button id="mobile-menu-toggle" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-semibold text-gray-800 ml-2">@yield('page_title', 'Dashboard')</h1>
            </div>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 px-6 py-6 overflow-auto">
            @yield('content')
        </main>
        
        @include('layouts.footer')
    </div>
    
    <!-- Mobile menu overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
    
    <script>
        // Basic SweetAlert2 Toast configuration with green theme
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#f0fdf4',
            color: '#166534',
            iconColor: '#22c55e'
        });

        // Green-themed SweetAlert2 configuration
        const GreenSwal = Swal.mixin({
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6b7280',
            background: '#ffffff',
            color: '#1f2937'
        });

        // Make Swal globally available
        window.Toast = Toast;
        window.Swal = GreenSwal;

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            
            if (mobileMenuToggle && sidebar && overlay) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
                
                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>