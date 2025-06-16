<?php

// Admin routes disabled - AdminController removed with Spatie permissions
// Uncomment and recreate AdminController if admin functionality is needed

/*
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // Add other admin routes as needed
});
*/
