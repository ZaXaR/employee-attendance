<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\Admin\AttendancesController;
use App\Http\Controllers\Admin\JobRoleController;
use App\Http\Controllers\Admin\LocationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', function () {
    $user = Auth::user();
    $today = Carbon::today('UTC')->toDateString();

    $todayRecord = AttendanceRecord::where('user_id', $user->id)
        ->where('work_date', $today)
        ->first();

    $records = AttendanceRecord::where('user_id', $user->id)
        ->orderByDesc('work_date')
        ->limit(30)
        ->get();

    $hasClockIn = $todayRecord !== null;
    $hasClockOut = $todayRecord && $todayRecord->clock_out !== null;

    return view('dashboard', compact('records', 'hasClockIn', 'hasClockOut'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/attendance/clock-in', [EmployeeAttendanceController::class, 'clockIn'])
        ->name('attendance.clock-in');

    Route::post('/attendance/clock-out', [EmployeeAttendanceController::class, 'clockOut'])
        ->name('attendance.clock-out');

    Route::get('/attendance', [EmployeeAttendanceController::class, 'index'])
        ->name('attendance.index');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

        // Users
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // Attendance
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendancesController::class, 'index'])->name('index');
            Route::post('/', [AttendancesController::class, 'store'])->name('store');
            Route::patch('/{record}', [AttendancesController::class, 'update'])->name('update');
            Route::delete('/{record}', [AttendancesController::class, 'destroy'])->name('destroy');
        });

        // Job Roles
        Route::resource('job-roles', JobRoleController::class)->except(['show']);

        // Locations
        Route::resource('locations', LocationController::class)->except(['show', 'destroy']);

    });




require __DIR__ . '/auth.php';
