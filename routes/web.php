<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamApplicationController;
use App\Http\Controllers\UnitController;
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
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users-datatable', [UserController::class, 'getUsersData'])->name('users.datatable');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');

    // Profile routes
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('profile', [UserController::class, 'updateOwnProfile'])->name('profile.update');
    Route::post('profile/password', [UserController::class, 'updateOwnPassword'])->name('profile.password');
    
    Route::get('events', [EventController::class, 'index'])->name('events.index');
    Route::get('events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('events', [EventController::class, 'store'])->name('events.store');
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('events-datatable', [EventController::class, 'getEventsData'])->name('events.datatable');
    
    Route::get('collaborations', [CollaborationController::class, 'index'])->name('collaborations.index');
    Route::get('collaborations/create', [CollaborationController::class, 'create'])->name('collaborations.create');
    Route::post('collaborations', [CollaborationController::class, 'store'])->name('collaborations.store');
    Route::get('collaborations/{collaboration}/edit', [CollaborationController::class, 'edit'])->name('collaborations.edit');
    Route::put('collaborations/{collaboration}', [CollaborationController::class, 'update'])->name('collaborations.update');
    Route::get('collaborations/{collaboration}', [CollaborationController::class, 'show'])->name('collaborations.show');
    Route::delete('collaborations/{collaboration}', [CollaborationController::class, 'destroy'])->name('collaborations.destroy');
    Route::get('collaborations-datatable', [CollaborationController::class, 'getCollaborationsData'])->name('collaborations.datatable');
    Route::patch('collaborations/{collaboration}/toggle-active', [CollaborationController::class, 'toggleActive'])->name('collaborations.toggle-active');
    
    Route::get('units', [UnitController::class, 'index'])->name('units.index');
    Route::get('units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('units', [UnitController::class, 'store'])->name('units.store');
    Route::get('units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
    Route::get('units-datatable', [UnitController::class, 'getUnitsData'])->name('units.datatable');

    Route::prefix('event-registrations')->name('event-registrations.')->group(function () {
        Route::get('/', [EventRegistrationController::class, 'index'])->name('index');
        Route::get('/{event}', [EventRegistrationController::class, 'eventRegistrations'])->name('event');
        Route::get('/{event}/registrations/{registration}', [EventRegistrationController::class, 'show'])->name('show');
    });

    // Team Application routes
    Route::prefix('team-applications')->name('team-applications.')->group(function () {
        Route::get('/', [TeamApplicationController::class, 'index'])->name('index');
        Route::get('/datatable', [TeamApplicationController::class, 'getApplicationsData'])->name('datatable');
        Route::get('/{id}/download', [TeamApplicationController::class, 'download'])->name('download');
        Route::post('/{id}/approve', [TeamApplicationController::class, 'approve'])->name('approve');
        Route::delete('/{id}', [TeamApplicationController::class, 'destroy'])->name('destroy');
    });
});
use App\Http\Controllers\ContactMessageController;

Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/admin/contacts', [ContactMessageController::class, 'index'])->name('admin.contacts.index');
    Route::get('/admin/contacts/datatable', [ContactMessageController::class, 'getMessagesData'])->name('admin.contacts.datatable');
    Route::get('/admin/contacts/user/{user}/messages', [ContactMessageController::class, 'getUserMessages'])->name('admin.contacts.messages');
    Route::patch('/admin/contacts/user/{user}/read', [ContactMessageController::class, 'markRead'])->name('admin.contacts.markRead');
    Route::delete('/admin/contacts/user/{user}', [ContactMessageController::class, 'destroy'])->name('admin.contacts.destroy');
});
