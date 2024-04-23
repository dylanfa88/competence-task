<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use \App\Http\Controllers\SalesController;

// Laravel Routes

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Custom Routes

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/sales', function () {
    return Inertia::render('Sales');
})->middleware(['auth', 'verified'])->name('sales');

Route::get('/get-sales', [SalesController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('sales.getSales');

Route::post('/sales', [SalesController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('sales.recordSale');

Route::post('/sales/get-selling-price', [SalesController::class, 'getSellingPrice'])
    ->middleware(['auth', 'verified'])
    ->name('sales.getSellingPrice');
