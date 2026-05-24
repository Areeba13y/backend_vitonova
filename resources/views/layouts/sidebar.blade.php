<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center mr-3">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-800">Admin Panel</h1>
                    <p class="text-xs text-gray-500">Management System</p>
                </div>
            </div>
            <!-- Mobile Close Button -->
            <button type="button" id="mobile-menu-close" class="lg:hidden cursor-pointer" aria-label="Close menu">
                <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie w-5 text-gray-400"></i>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>
            
            <!-- Team Members -->
            <a href="{{ route('users.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 text-gray-400"></i>
                <span class="ml-3 font-medium">Users</span>
            </a>

            <!-- Units -->
            <a href="{{ route('units.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('units.*') ? 'active' : '' }}">
                <i class="fas fa-building w-5 text-gray-400"></i>
                <span class="ml-3 font-medium">Units</span>
            </a>

            <!-- Event Management -->
            <a href="{{ route('events.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                <span class="ml-3 font-medium">Events</span>
            </a>

            <!-- Collaborations -->
            <a href="{{ route('collaborations.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('collaborations.*') ? 'active' : '' }}">
                <i class="fas fa-handshake w-5 text-gray-400"></i>
                <span class="ml-3 font-medium">Collaborations</span>
            </a>

            <!-- Team Applications -->
            <a href="{{ route('team-applications.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('team-applications.*') ? 'active' : '' }}">
                <i class="fas fa-user-plus w-5 text-gray-400"></i>
                <span class="ml-3 font-medium">Applications</span>
            </a>

            <!-- Contact Messages -->
            <a href="{{ route('admin.contacts.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="fas fa-envelope w-5 text-gray-400"></i>
                <span class="ml-3 font-medium">Messages</span>
            </a>
        </nav>
        
        <!-- User Profile & Logout -->
        <div class="border-t border-gray-100 p-4">
            <a href="{{ route('profile') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors mb-2 {{ request()->routeIs('profile') ? 'bg-green-50' : '' }}">
                @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover mr-3">
                @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center text-white font-semibold mr-3">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </a>
            
            <!-- Profile Button -->
            <a href="{{ route('profile') }}" id="sidebarProfileBtn" class="w-full flex items-center justify-center px-4 py-2.5 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors text-sm font-medium mb-2">
                <i class="fas fa-user mr-2"></i>
                <span>My Profile</span>
            </a>
            
            <!-- Logout Button -->
            <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" id="sidebarLogoutBtn" class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-medium">
                    <i id="sidebarLogoutIcon" class="fas fa-sign-out-alt mr-2"></i>
                    <span id="sidebarLogoutText">Logout</span>
                    <i id="sidebarLogoutLoader" class="fas fa-spinner fa-spin hidden ml-2"></i>
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
                
                document.getElementById('sidebarLogoutIcon').classList.add('hidden');
                document.getElementById('sidebarLogoutText').classList.add('hidden');
                document.getElementById('sidebarLogoutLoader').classList.remove('hidden');
                
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
        }
        
        // Sidebar Profile Button loader
        const sidebarProfileBtn = document.getElementById('sidebarProfileBtn');
        if (sidebarProfileBtn) {
            sidebarProfileBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const span = this.querySelector('span');
                icon.classList.add('hidden');
                span.classList.add('hidden');
                const loader = document.createElement('i');
                loader.className = 'fas fa-spinner fa-spin';
                this.appendChild(loader);
            });
        }
        
        // Reset loaders on page load (only for profile button)
        const loaders = sidebarProfileBtn ? sidebarProfileBtn.querySelectorAll('i.fa-spinner') : [];
        if (loaders.length > 0) {
            sidebarProfileBtn.querySelectorAll('i').forEach(function(icon) {
                icon.classList.remove('hidden');
            });
            sidebarProfileBtn.querySelectorAll('span').forEach(function(span) {
                span.classList.remove('hidden');
            });
            loaders.forEach(function(loader) {
                loader.remove();
            });
        }
    });
</script>
