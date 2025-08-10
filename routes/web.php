<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\Admin\AttendancesController;


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
    return view('welcome');
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

// All admin routes are protected by 'auth' and 'admin' middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/attendance', [AttendancesController::class, 'index'])->name('admin.attendance.index');
    Route::post('/attendance', [AttendancesController::class, 'store'])->name('admin.attendance.store');
    Route::patch('/attendance/{record}', [AttendancesController::class, 'update'])->name('admin.attendance.update');
    Route::delete('/attendance/{record}', [AttendancesController::class, 'destroy'])->name('admin.attendance.destroy');

});



require __DIR__ . '/auth.php';
