<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- jQuery (must be first) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#22c55e',
                        primaryDark: '#16a34a',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f3f4f6;
        }
        .sidebar-link.active {
            background: linear-gradient(to right, #22c55e, #22c55e) !important;
            color: white !important;
        }
        .sidebar-link.active .text-gray-400 {
            color: white !important;
        }
        .dataTables_wrapper {
            font-family: inherit;
        }
        table.dataTable thead th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem !important;
            border-bottom: 1px solid #e5e7eb !important;
        }
        table.dataTable tbody tr:hover {
            background-color: #f9fafb !important;
        }
        table.dataTable tbody td {
            padding: 1rem !important;
            vertical-align: middle;
        }
        .table-actions {
            display: flex;
            gap: 0.5rem;
        }
        .dt-button {
            background: #22c55e !important;
            color: white !important;
            border: none !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
        }
        /* Hamburger Menu Styles */
        .hamburger input {
            display: none;
        }
        .hamburger svg {
            height: 2.5rem;
            transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .line {
            fill: none;
            stroke: #22c55e;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 3;
            transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
                        stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .line-top-bottom {
            stroke-dasharray: 12 63;
        }
        .hamburger input:checked + svg {
            transform: rotate(-45deg);
        }
        .hamburger input:checked + svg .line-top-bottom {
            stroke-dasharray: 20 300;
            stroke-dashoffset: -32.42;
        }
        .dt-button:hover {
            background: #16a34a !important;
        }
        .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }
        .dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }
        .paginate_button.current {
            background: #22c55e !important;
            border-color: #22c55e !important;
            color: white !important;
        }
        .dt-button {
            background: #22c55e !important;
            color: white !important;
            border: none !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            margin-right: 0.5rem !important;
        }
        .dt-button:hover {
            background: #16a34a !important;
        }
        .dt-buttons {
            margin-bottom: 0.5rem;
        }
        /* Actions Menu Hamburger Animation - Exact Match */
        .actions-menu {
            position: relative;
            display: inline-block;
        }
        .actions-menu .hamburger {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            transition: background 200ms;
        }
        .actions-menu .hamburger:hover {
            background: #f3f4f6;
        }
        .actions-menu .hamburger input {
            display: none;
        }
        .actions-menu .hamburger svg {
            width: 1.25rem;
            height: 1.25rem;
            transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .actions-menu .hamburger .line {
            fill: none;
            stroke: #6b7280;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 3;
            transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
                        stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .actions-menu .hamburger .line-top-bottom {
            stroke-dasharray: 12 63;
        }
        .actions-menu .hamburger input:checked + svg {
            transform: rotate(-45deg);
        }
        .actions-menu .hamburger input:checked + svg .line-top-bottom {
            stroke-dasharray: 20 300;
            stroke-dashoffset: -32.42;
        }
        .actions-menu {
            position: relative;
            overflow: visible !important;
        }
        .actions-menu .actions-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            min-width: 160px;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.95);
            transform-origin: top right;
            transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 9999 !important;
        }
        .actions-menu.active .actions-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            display: block !important;
        }
        .actions-menu .actions-dropdown a,
        .actions-menu .actions-dropdown button {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #374151;
            transition: all 150ms;
        }
        .actions-menu .actions-dropdown a:hover,
        .actions-menu .actions-dropdown button:hover {
            background: #f9fafb;
        }
        .actions-menu .actions-dropdown i {
            width: 1.75rem;
            text-align: center;
            flex-shrink: 0;
        }
        .actions-menu .actions-dropdown span {
            margin-left: 0.25rem;
        }
        /* DataTables Pagination - Professional Styling */
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0;
            margin-top: 0;
        }
        .dataTables_wrapper .paginate_button {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 2.25rem !important;
            height: 2.25rem !important;
            padding: 0 !important;
            margin: 0 0.125rem !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #6b7280 !important;
            background: white !important;
            cursor: pointer;
            transition: all 150ms ease !important;
        }
        .dataTables_wrapper .paginate_button:hover {
            background: #f3f4f6 !important;
            border-color: #d1d5db !important;
            color: #374151 !important;
        }
        .dataTables_wrapper .paginate_button.current {
            background: #22c55e !important;
            border-color: #22c55e !important;
            color: white !important;
            font-weight: 600 !important;
            box-shadow: 0 1px 3px rgba(34, 197, 94, 0.3);
        }
        .dataTables_wrapper .paginate_button.current:hover {
            background: #16a34a !important;
            border-color: #16a34a !important;
            color: white !important;
        }
        .dataTables_wrapper .paginate_button.disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }
        /* Fix horizontal scroll */
        .dataTables_wrapper {
            overflow: visible !important;
            max-width: 100%;
        }
        .dataTables_wrapper .dataTables_scroll {
            overflow: visible !important;
        }
        .dataTables_wrapper .dataTables_scrollBody {
            overflow: visible !important;
        }
        .dataTables_wrapper table {
            width: 100% !important;
            table-layout: auto !important;
        }
        .dataTables_wrapper table tbody tr {
            overflow: visible !important;
        }
        .dataTables_wrapper table td {
            overflow: visible !important;
        }
        .dataTables_wrapper table th,
        .dataTables_wrapper table td {
            white-space: nowrap;
            overflow: visible;
        }
        .dataTables_wrapper table td:last-child,
        .dataTables_wrapper table th:last-child {
            text-align: right;
        }
        .dataTables_wrapper .paginate_button.disabled:hover {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }
        .dataTables_wrapper .dataTables_info {
            padding-top: 0;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .dataTables_wrapper .dataTables_paginate,
        .dataTables_wrapper .dataTables_info {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .dataTables_wrapper .dataTables_paginate {
            float: right;
        }
        .dataTables_wrapper .dataTables_info {
            float: left;
        }
        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1rem;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
            background: white;
            cursor: pointer;
        }
        .dataTables_wrapper .dataTables_length select:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.1);
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            width: 16rem;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.1);
        }
        /* Keep processing indicator visible and scoped to each table wrapper */
        .dataTables_wrapper {
            position: relative;
        }
        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 30;
            min-width: 100px;
            max-width: 150px;
            text-align: center;
            border: 1px solid #bbf7d0;
            background: rgba(240, 253, 244, 0.96);
            color: #166534;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(17, 24, 39, 0.08);
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            height: auto;
        }
        /* Prevent horizontal scroll */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw;
        }
        .main-content {
            overflow-x: hidden !important;
            max-width: 100%;
        }
        .rotate-180 {
            transform: rotate(180deg);
        }
        /* Green Theme Loader */
        .loader-green {
            border-color: #22c55e !important;
            border-top-color: transparent !important;
        }
        /* Green input focus */
        input:focus, textarea:focus, select:focus {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2) !important;
        }
        /* Green file input */
        .file\:bg-green-50::file-selector-button {
            background-color: #f0fdf4 !important;
            color: #166534 !important;
        }
        .file\:text-green-700::file-selector-button {
            color: #166534 !important;
        }
        .file\:hover\\:bg-green-100::file-selector-button:hover {
            background-color: #dcfce7 !important;
        }
        /* Green checkbox/radio */
        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background-color: #22c55e !important;
            border-color: #22c55e !important;
        }
        /* Profile green accent */
        .bg-green-accent {
            background-color: #22c55e !important;
        }
    </style>
    <script>
        window.baseUrl = '{{ url("/") }}';
    </script>
</head>
<body class="min-h-screen flex overflow-x-hidden" style="background-color: #f3f4f6;">
    @include('layouts.sidebar')
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen ml-0 lg:ml-64 main-content">
        <!-- Top Bar -->
        <div class="bg-white border-b border-gray-200 px-3 sm:px-4 lg:px-6 py-3 sm:py-4 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center space-x-4">
                <button id="mobile-menu-toggle" class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors" aria-label="Open menu">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <h1 class="text-lg sm:text-xl font-semibold text-gray-800 truncate">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button id="userDropdownBtn" class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 rounded-lg px-2 py-1 transition-colors">
                        @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover">
                        @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        @endif
                        <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="dropdownArrow"></i>
                    </button>
                    
                    <div id="userDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 hidden z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'Admin User' }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                        </div>
                        <a href="{{ route('profile') }}" id="profileLink" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user w-5 text-gray-400"></i>
                            <span class="ml-3 text-sm font-medium">My Profile</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form id="navbar-logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" id="logoutBtn" class="w-full flex items-center px-4 py-2.5 text-red-600 hover:bg-red-50 transition-colors">
                                <i id="logoutIcon" class="fas fa-sign-out-alt w-5"></i>
                                <span id="logoutText" class="ml-3 text-sm font-medium">Logout</span>
                                <i id="logoutLoader" class="fas fa-spinner fa-spin w-5 hidden"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
            @yield('content')
        </main>
        
        @include('layouts.footer')
    </div>
    
    <!-- Mobile menu overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        function toggleActionsMenu(checkbox) {
            const menuEl = checkbox.closest('.actions-menu');
            if (checkbox.checked) {
                // Close all other menus first
                $('.actions-menu').removeClass('active');
                $('.actions-dropdown').hide();
                $('.actions-menu .hamburger input').prop('checked', false);
                // Open this menu
                checkbox.checked = true;
                menuEl.classList.add('active');
                menuEl.querySelector('.actions-dropdown').style.display = 'block';
            } else {
                menuEl.classList.remove('active');
                menuEl.querySelector('.actions-dropdown').style.display = 'none';
            }
        }

        // Wait for jQuery to be ready
        jQuery(document).ready(function($) {
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

            window.Toast = Toast;
            window.Swal = Swal.mixin({
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280'
            });

            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');

            function openMobileMenu() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileMenu() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            if (mobileMenuToggle && sidebar && overlay) {
                mobileMenuToggle.addEventListener('click', function() {
                    if (sidebar.classList.contains('-translate-x-full')) {
                        openMobileMenu();
                    } else {
                        closeMobileMenu();
                    }
                });
                
                overlay.addEventListener('click', function() {
                    closeMobileMenu();
                });

                if (mobileMenuClose) {
                    mobileMenuClose.addEventListener('click', function() {
                        closeMobileMenu();
                    });
                }

                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) {
                        overlay.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            }

            // DataTables defaults
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        previous: '<i class="fas fa-angle-left"></i>'
                    },
                    emptyTable: "No data available in table",
                    zeroRecords: "No matching records found"
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                scrollCollapse: true,
                scrollX: false,
                scrollY: false
            });
            
            // Close dropdowns when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.actions-menu').length) {
                    $('.actions-menu').removeClass('active');
                    $('.actions-dropdown').hide();
                    $('.actions-menu .hamburger input').prop('checked', false);
                }
                if (!$(e.target).closest('#userDropdownBtn').length && !$(e.target).closest('#userDropdown').length) {
                    $('#userDropdown').addClass('hidden');
                    $('#dropdownArrow').removeClass('rotate-180');
                }
            });
            
            // User dropdown toggle
            $('#userDropdownBtn').on('click', function(e) {
                e.stopPropagation();
                $('#userDropdown').toggleClass('hidden');
                $('#dropdownArrow').toggleClass('rotate-180');
            });
            
            // Navbar logout form
            $('#navbar-logout-form').on('submit', function(e) {
                e.preventDefault();
                $('#logoutIcon').addClass('hidden');
                $('#logoutText').addClass('hidden');
                $('#logoutLoader').removeClass('hidden');
                
                const formData = new FormData(this);
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Logged out successfully!'
                        });
                        setTimeout(() => {
                            window.location.href = '{{ url("/login") }}';
                        }, 1000);
                    }
                });
            });
            
            // Navbar profile link loader
            $('#profileLink').on('click', function(e) {
                const icon = $(this).find('i').not('#logoutIcon, #logoutLoader');
                const span = $(this).find('span');
                icon.addClass('hidden');
                span.addClass('hidden');
                $(this).append('<i class="fas fa-spinner fa-spin w-5 text-gray-400"></i>');
            });
            
            // Reset loaders on page load (in case user came back with back button)
            resetNavLoaders();
        });
        
        function resetNavLoaders() {
            document.querySelectorAll('#profileLink').forEach(function(el) {
                const loaders = el.querySelectorAll('i.fa-spinner');
                if (loaders.length > 0) {
                    el.querySelectorAll('i').forEach(function(icon) {
                        icon.classList.remove('hidden');
                    });
                    el.querySelectorAll('span').forEach(function(span) {
                        span.classList.remove('hidden');
                    });
                    loaders.forEach(function(loader) {
                        loader.remove();
                    });
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
