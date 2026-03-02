<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Test route to verify everything is working
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Application is working correctly',
        'routes' => [
            'dashboard' => route('dashboard'),
            'users.index' => route('users.index'),
            'login' => route('login')
        ]
    ]);
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // User CRUD routes
    Route::resource('users', UserController::class);
});
