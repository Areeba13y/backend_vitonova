<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamApplicationController;
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
            'events.index' => route('events.index'),
            'event_registrations.index' => route('event-registrations.index'),
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
    Route::resource('events', EventController::class);

    Route::prefix('event-registrations')->name('event-registrations.')->group(function () {
        Route::get('/', [EventRegistrationController::class, 'index'])->name('index');
        Route::get('/{event}', [EventRegistrationController::class, 'eventRegistrations'])->name('event');
        Route::get('/{event}/registrations/{registration}', [EventRegistrationController::class, 'show'])->name('show');
    });

    // Team Application routes
    Route::prefix('team-applications')->name('team-applications.')->group(function () {
        Route::get('/', [TeamApplicationController::class, 'index'])->name('index');
        Route::get('/{id}/download', [TeamApplicationController::class, 'download'])->name('download');
        Route::post('/{id}/approve', [TeamApplicationController::class, 'approve'])->name('approve');
        Route::delete('/{id}', [TeamApplicationController::class, 'destroy'])->name('destroy');
    });
});
