<header class="bg-gradient-to-r from-green-200 via-green-100 to-white text-gray-800 shadow-lg">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Left side - Logo and Title -->
            <div class="flex items-center space-x-3">
                <div class="bg-green-500 bg-opacity-20 p-2 rounded-lg backdrop-blur-sm border border-green-300">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800 drop-shadow-sm">@yield('page_title', 'Dashboard')</h1>
            </div>
            
            <!-- Center - Navigation -->
            <nav class="hidden md:flex space-x-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg bg-green-500 bg-opacity-20 backdrop-blur-sm text-sm font-medium text-green-700 hover:bg-green-500 hover:bg-opacity-30 transition-all duration-200 border border-green-300">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2 rounded-lg bg-green-500 bg-opacity-20 backdrop-blur-sm text-sm font-medium text-green-700 hover:bg-green-500 hover:bg-opacity-30 transition-all duration-200 border border-green-300">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    Users
                </a>
            </nav>
            
            <!-- Right side - Logout Button -->
            <div class="flex items-center space-x-4">
                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center px-3 py-2 rounded-lg bg-green-500 bg-opacity-20 backdrop-blur-sm hover:bg-green-500 hover:bg-opacity-30 text-sm font-medium text-green-700 transition-all duration-200 border border-green-300">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                        </svg>
                        <span class="logout-text">Logout</span>
                        <span class="logout-loading hidden">Logging out...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    // Load on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Handle logout form
        const logoutForm = document.getElementById('logout-form');
        
        if (logoutForm) {
            logoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button');
                const logoutText = button.querySelector('.logout-text');
                const logoutLoading = button.querySelector('.logout-loading');
                
                // Show loading state
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
                    
                    // Reset button state
                    button.disabled = false;
                    logoutText.classList.remove('hidden');
                    logoutLoading.classList.add('hidden');
                });
            });
        }
    });
</script>