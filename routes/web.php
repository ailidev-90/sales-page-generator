<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/sales-pages/{salesPage}/export-html', [SalesPageController::class, 'exportHtml'])
        ->name('sales-pages.export-html');
    Route::resource('sales-pages', SalesPageController::class)
        ->parameters(['sales-pages' => 'salesPage']);
});

require __DIR__.'/auth.php';
