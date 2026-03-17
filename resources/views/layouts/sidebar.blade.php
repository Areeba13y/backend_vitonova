<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand -->
        <div class="flex items-center px-6 py-4 border-b border-gray-200">
            <div class="bg-gradient-to-r from-green-400 to-green-500 p-2 rounded-lg mr-3">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                </svg>
            </div>
            <h1 class="text-lg font-bold text-gray-800">Admin System</h1>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <!-- User Management -->
            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span class="font-medium">User Management</span>
            </a>

            <!-- Event Management -->
            <a href="{{ route('events.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors duration-200 {{ request()->routeIs('events.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                </svg>
                <span class="font-medium">Event Management</span>
            </a>

            <!-- Event Registrations -->
            <a href="{{ route('event-registrations.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors duration-200 {{ request()->routeIs('event-registrations.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5V4H2v16h5v-2H4V6h16v12h-3v2zM9 8h9v2H9V8zm0 4h9v2H9v-2zm0 4h5v2H9v-2zm-4 0l2 2 4-4-1.41-1.41L7 15.17l-.59-.58L5 16z"/>
                </svg>
                <span class="font-medium">Event Registrations</span>
            </a>

            <!-- Team Applications -->
            <a href="{{ route('team-applications.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors duration-200 {{ request()->routeIs('team-applications.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="font-medium">Team Applications</span>
            </a>

            <!-- Contact Messages -->
            <a href="{{ route('admin.contacts.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors duration-200 {{ request()->routeIs('admin.contacts.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                <span class="font-medium">Contact Messages</span>
            </a>
            
        </nav>
        
        <!-- User Profile & Logout -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center mb-4">
                <div class="bg-gray-300 rounded-full p-2 mr-3">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                </div>
            </div>
            
            <!-- Logout Button -->
            <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    <span class="sidebar-logout-text">Logout</span>
                    <span class="sidebar-logout-loading hidden">Logging out...</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutForm = document.getElementById('sidebar-logout-form');
        
        if (logoutForm) {
            logoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button');
                const logoutText = button.querySelector('.sidebar-logout-text');
                const logoutLoading = button.querySelector('.sidebar-logout-loading');
                
                button.disabled = true;
                logoutText.classList.add('hidden');
                logoutLoading.classList.remove('hidden');
                
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
                            title: 'Logged out successfully! See you soon.'
                        });
                        setTimeout(() => {
                            window.location.href = '{{ url("/login") }}';
                        }, 1500);
                    } else {
                        throw new Error('Logout failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Logout Error',
                        text: 'Error logging out. Please try again.',
                        confirmButtonText: 'Try Again'
                    });
                    
                    button.disabled = false;
                    logoutText.classList.remove('hidden');
                    logoutLoading.classList.add('hidden');
                });
            });
        }
    });
</script>