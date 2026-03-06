<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/events/upcoming', [EventController::class, 'upcoming']);
Route::post('/events/{event}/register', [EventController::class, 'register']);
