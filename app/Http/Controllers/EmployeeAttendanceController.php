<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class EmployeeAttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today('UTC')->toDateString();

        $todayRecord = AttendanceRecord::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        return view('employee.attendance', [
            'records' => AttendanceRecord::where('user_id', $user->id)->orderByDesc('work_date')->limit(30)->get(),
            'hasClockIn' => $todayRecord !== null,
            'hasClockOut' => $todayRecord && $todayRecord->clock_out !== null,
        ]);
    }

    public function clockIn()
    {
        $userId = Auth::id();
        $nowUtc = now()->setTimezone('UTC');
        $workDate = $nowUtc->toDateString();

        try {
            AttendanceRecord::create([
                'user_id' => $userId,
                'work_date' => $workDate,
                'clock_in' => $nowUtc,
            ]);
        } catch (QueryException $e) {
            // Handles race condition when UNIQUE(user_id, work_date) is violated
            return back()->with('error', 'Already clocked in today.');
        }

        return back()->with('success', 'Clock In recorded.');
    }

    public function clockOut()
    {
        $userId = Auth::id();
        $nowUtc = now()->setTimezone('UTC');
        $workDate = $nowUtc->toDateString();

        $record = AttendanceRecord::where('user_id', $userId)
            ->where('work_date', $workDate)
            ->first();

        if (!$record) {
            return back()->with('error', 'No clock-in found.');
        }
        if ($record->clock_out !== null) {
            return back()->with('error', 'Already clocked out.');
        }
        if ($nowUtc->lt($record->clock_in)) {
            return back()->with('error', 'Clock out time cannot be before clock in.');
        }

        $record->update(['clock_out' => $nowUtc]);

        return back()->with('success', 'Clock Out recorded.');
    }
}
