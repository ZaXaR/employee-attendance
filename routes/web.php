<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AttendancesController;
use App\Http\Controllers\Admin\JobRoleController;
use App\Http\Controllers\Admin\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔐 Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// 🔒 Authenticated user routes
Route::middleware('auth')->group(function () {
    // ✅ User attendance dashboard (dashboard.blade.php)
    Route::get('/dashboard', [AttendancesController::class, 'dashboard'])->name('dashboard');

    // ✅ User creates attendance record
    Route::post('/attendance/store', [AttendancesController::class, 'store'])->name('attendance.store');

    // 👤 Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🛡️ Admin-only routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // 🧭 Admin dashboard
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

        // 👥 User management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // 📅 Attendance management
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendancesController::class, 'index'])->name('index');
            Route::post('/', [AttendancesController::class, 'store'])->name('store');
            Route::patch('/{record}', [AttendancesController::class, 'update'])->name('update');
            Route::delete('/{record}', [AttendancesController::class, 'destroy'])->name('destroy');
        });

        // 🧩 Job roles
        Route::resource('job-roles', JobRoleController::class)->except(['show']);

        // 📍 Locations
        Route::resource('locations', LocationController::class)->except(['show', 'destroy']);
    });

// 🔐 Auth scaffolding
require __DIR__ . '/auth.php';