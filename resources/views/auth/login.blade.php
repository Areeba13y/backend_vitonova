<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Admin System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .green-gradient-bg {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #166534 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .green-gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.15);
        }
        .green-glow {
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
        }
        /* Override any default blue colors */
        input:focus {
            outline: none !important;
            border-color: #22c55e !important;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2) !important;
        }
        button:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3) !important;
        }
        a:focus {
            outline: none !important;
            color: #16a34a !important;
        }
        /* Remove any blue link colors */
        a {
            color: #16a34a;
        }
        a:hover {
            color: #15803d;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-950 via-green-900 to-green-950 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-green-400 to-green-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-green-500 to-green-700 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-40 left-40 w-60 h-60 bg-gradient-to-br from-green-300 to-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-animation" style="animation-delay: 4s;"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo and Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-400 to-green-500 rounded-2xl mb-4 shadow-2xl green-glow">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2 tracking-tight">Admin System</h1>
            <p class="text-green-200 text-lg">Welcome back! Please sign in to continue</p>
        </div>
        
        <!-- Login Form Card -->
        <div class="glass-effect rounded-2xl px-8 py-10 shadow-2xl">
            <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">
                        Email or Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email or username"
                            class="input-focus w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-gray-50 text-gray-900 placeholder-gray-500"
                            required
                        >
                    </div>
                    <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            class="input-focus w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-gray-50 text-gray-900 placeholder-gray-500"
                            required
                        >
                    </div>
                    <div id="password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Sign In Button -->
                <button 
                    type="submit" 
                    id="login-btn"
                    class="w-full bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:from-green-600 hover:via-green-700 hover:to-green-800 text-white font-semibold py-4 px-4 rounded-xl transition-all duration-300 flex items-center justify-center disabled:opacity-50 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 green-glow"
                >
                    <span class="btn-content flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                        </svg>
                        Sign In
                    </span>
                    <span class="btn-loading hidden flex items-center">
                        <svg class="animate-spin w-5 h-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing In...
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V5l-9-4z"/>
                    </svg>
                    Secure login powered by advanced encryption
                </p>
            </div>
        </div>
    </div>
    
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

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('login-form');
        const loginBtn = document.getElementById('login-btn');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        
        // Add smooth focus animations
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            
            // Show loading state
            loginBtn.disabled = true;
            loginBtn.querySelector('.btn-content').classList.add('hidden');
            loginBtn.querySelector('.btn-loading').classList.remove('hidden');
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Welcome back! Login successful.'
                    });
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: data.message,
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#22c55e'
                    });
                    
                    // Show field errors
                    if (data.errors) {
                        if (data.errors.email) {
                            emailError.textContent = data.errors.email[0];
                            emailError.classList.remove('hidden');
                        }
                        if (data.errors.password) {
                            passwordError.textContent = data.errors.password[0];
                            passwordError.classList.remove('hidden');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Unable to connect to the server. Please check your connection and try again.',
                    confirmButtonText: 'Retry',
                    confirmButtonColor: '#22c55e'
                });
            })
            .finally(() => {
                // Reset button state
                loginBtn.disabled = false;
                loginBtn.querySelector('.btn-content').classList.remove('hidden');
                loginBtn.querySelector('.btn-loading').classList.add('hidden');
            });
        });
    });
    </script>
</body>
</html>