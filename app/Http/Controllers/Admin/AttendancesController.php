<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;



class AttendancesController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->select('id', 'name')->get();

        $filters = [
            'user_id' => $request->integer('user_id'),
            'from'    => $request->input('from'),
            'to'      => $request->input('to'),
        ];

        $from = $filters['from'] ? Carbon::parse($filters['from'], 'UTC')->startOfDay() : null;
        $to   = $filters['to']   ? Carbon::parse($filters['to'], 'UTC')->endOfDay()   : null;

        $records = AttendanceRecord::with('user')
            ->when($filters['user_id'], fn ($q) => $q->where('user_id', $filters['user_id']))
            ->when($from, fn ($q) => $q->whereDate('work_date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('work_date', '<=', $to))
            ->orderByDesc('work_date')
            ->orderByDesc('clock_in')
            ->paginate(20)
            ->withQueryString();

        return view('admin.attendance.index', compact('users', 'records', 'filters'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request, isUpdate: false);

        [$clockIn, $clockOut] = $this->assembleTimes($data['work_date'], $data['clock_in'], $data['clock_out']);

        if ($clockIn && $clockOut && $clockOut->lte($clockIn)) {
            return back()->withErrors(['clock_out' => 'Clock out must be after clock in'])->withInput();
        }

        AttendanceRecord::create([
            'user_id'   => $data['user_id'],
            'work_date' => $data['work_date'],
            'clock_in'  => $clockIn,
            'clock_out' => $clockOut,
        ]);

        return back()->with('success', 'Record created.');
    }

    public function update(Request $request, AttendanceRecord $record)
    {
        $data = $this->validatePayload($request, isUpdate: true);

        [$clockIn, $clockOut] = $this->assembleTimes($data['work_date'], $data['clock_in'], $data['clock_out']);

        if ($clockIn && $clockOut && $clockOut->lte($clockIn)) {
            return back()->withErrors(['clock_out' => 'Clock out must be after clock in'])->withInput();
        }

        $record->update([
            'user_id'   => $data['user_id'],
            'work_date' => $data['work_date'],
            'clock_in'  => $clockIn,
            'clock_out' => $clockOut,
        ]);

        return back()->with('success', 'Record updated.');
    }

    public function destroy(AttendanceRecord $record)
    {
        $record->delete();
        return back()->with('success', 'Record deleted.');
    }

    private function validatePayload(Request $request, bool $isUpdate): array
    {
        return $request->validate([
            'user_id'   => ['required', Rule::exists('users', 'id')],
            'work_date' => ['required', 'date'],
            // HH:MM в UTC; можно оставить пустым
            'clock_in'  => ['nullable', 'date_format:H:i'],
            'clock_out' => ['nullable', 'date_format:H:i'],
        ]);
    }

    // Собираем UTC datetime из даты и времени; если time пустой — вернём null
    private function assembleTimes(string $date, ?string $in, ?string $out): array
    {
        $clockIn  = $in  ? Carbon::parse("$date $in",  'UTC')->setTimezone('UTC') : null;
        $clockOut = $out ? Carbon::parse("$date $out", 'UTC')->setTimezone('UTC') : null;
        return [$clockIn, $clockOut];
    }
}
