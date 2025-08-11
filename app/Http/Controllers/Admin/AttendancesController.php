<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Location;

class AttendancesController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->select('id', 'name')->get();
        $locations = Location::orderBy('name')->select('id', 'name')->get();

        $filters = [
            'user_id' => $request->integer('user_id'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
        ];

        $from = $filters['from'] ? Carbon::parse($filters['from'], 'UTC')->startOfDay() : null;
        $to = $filters['to'] ? Carbon::parse($filters['to'], 'UTC')->endOfDay() : null;

        $records = AttendanceRecord::with(['user:id,name', 'location:id,name'])
            ->when($filters['user_id'], fn($q) => $q->where('user_id', $filters['user_id']))
            ->when($from, fn($q) => $q->whereDate('work_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('work_date', '<=', $to))
            ->orderByDesc('work_date')
            ->orderByDesc('clock_in')
            ->paginate(20)
            ->withQueryString();
            
        return view('admin.attendance.index', compact('users', 'locations', 'records', 'filters'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);
        [$clockIn, $clockOut] = $this->assembleTimes($data['work_date'], $data['clock_in'], $data['clock_out']);

        if ($clockIn && $clockOut && $clockOut->lte($clockIn)) {
            return back()->withErrors(['clock_out' => 'Clock out must be after clock in'])->withInput();
        }

        AttendanceRecord::create([
            'user_id' => $data['user_id'],
            'work_date' => $data['work_date'],
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'location_id' => $data['location_id'] ?? null,
            'break_time' => $data['break_time'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Record created.');
    }

    public function update(Request $request, AttendanceRecord $record)
    {
        $user = $request->user();

        // Only admin or record owner can update
        if (!$user->isAdmin() && $user->id !== $record->user_id) {
            abort(403);
        }

        $data = $this->validatePayload($request);
        [$clockIn, $clockOut] = $this->assembleTimes($data['work_date'], $data['clock_in'], $data['clock_out']);

        if ($clockIn && $clockOut && $clockOut->lte($clockIn)) {
            return back()->withErrors(['clock_out' => 'Clock out must be after clock in'])->withInput();
        }

        // Only admin can clear time fields
        if (!$user->isAdmin()) {
            $clockIn = $record->clock_in;
            $clockOut = $record->clock_out;
        }

        $record->update([
            'user_id' => $data['user_id'],
            'work_date' => $data['work_date'],
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'location_id' => $data['location_id'] ?? null,
            'break_time' => $data['break_time'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Record updated.');
    }

    public function destroy(Request $request, AttendanceRecord $record)
    {
        // Only admin can delete records
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $record->delete();
        return back()->with('success', 'Record deleted.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'work_date' => ['required', 'date'],
            'clock_in' => ['nullable', 'date_format:H:i'],
            'clock_out' => ['nullable', 'date_format:H:i'],
            'location_id' => ['nullable', Rule::exists('locations', 'id')],
            'break_time' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function assembleTimes(string $date, ?string $in, ?string $out): array
    {
        $clockIn = $in ? Carbon::parse("$date $in", 'UTC')->setTimezone('UTC') : null;
        $clockOut = $out ? Carbon::parse("$date $out", 'UTC')->setTimezone('UTC') : null;
        return [$clockIn, $clockOut];
    }
}