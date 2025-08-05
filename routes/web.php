<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BudgetEntryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    Route::get('itineraries/{itinerary}/export/excel', [ItineraryController::class, 'exportExcel'])
        ->name('itineraries.export.excel');
    Route::get('itineraries/{itinerary}/export/pdf', [ItineraryController::class, 'exportPdf'])
        ->name('itineraries.export.pdf');
    Route::resource('itineraries.activities', ActivityController::class)->shallow();
    Route::resource('itineraries.group-members', GroupMemberController::class)->shallow()->only(['store', 'update', 'destroy']);
    Route::resource('itineraries.budgets', BudgetEntryController::class)
        ->shallow()
        ->parameters(['budgets' => 'budgetEntry']);
    Route::get('budgets/{budgetEntry}/spent', [BudgetEntryController::class, 'editSpent'])->name('budgets.edit-spent');
    Route::patch('budgets/{budgetEntry}/spent', [BudgetEntryController::class, 'updateSpent'])->name('budgets.update-spent');
    Route::post('budgets/{budgetEntry}/participants/{member}/toggle', [BudgetEntryController::class, 'togglePaid'])->name('budgets.toggle-paid');
    Route::resource('itineraries.bookings', BookingController::class)->shallow()->only(['store', 'update', 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/itineraries/create', [ItineraryController::class, 'create'])->name('itineraries.create');
    Route::post('/itineraries', [ItineraryController::class, 'store'])->name('itineraries.store');
    // web.php
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::resource('activities', controller: ActivityController::class);
});

require __DIR__.'/auth.php';
