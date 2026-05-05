<?php

use App\Http\Controllers\TeamApplicationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events/upcoming', [EventController::class, 'upcoming']);
    Route::get('/collaborations', [CollaborationController::class, 'list']);
    Route::post('/events/{event}/register', [EventController::class, 'register']);
    Route::post('/join-team', [TeamApplicationController::class, 'store']);
    Route::get('/users', [UserController::class, 'apiGetUsers']);
    Route::post('/contact', [ContactMessageController::class, 'store']);
});
