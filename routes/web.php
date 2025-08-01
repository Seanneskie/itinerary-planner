<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupMemberController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('itineraries', ItineraryController::class);
    Route::resource('itineraries.activities', ActivityController::class)->shallow();
    Route::resource('itineraries.group-members', GroupMemberController::class)->shallow()->only(['store','update','destroy']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/itineraries/create', [ItineraryController::class, 'create'])->name('itineraries.create');
    Route::post('/itineraries', [ItineraryController::class, 'store'])->name('itineraries.store');
    // web.php
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::resource('activities', controller: ActivityController::class);
});

require __DIR__.'/auth.php';
