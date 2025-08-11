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
            'location_id' => $request->integer('location_id'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
        ];

        if (!$filters['from'] && !$filters['to']) {
            $from = now('UTC')->startOfDay();
            $to = now('UTC')->endOfDay();
        } else {
            $from = $filters['from'] ? Carbon::parse($filters['from'], 'UTC')->startOfDay() : null;
            $to = $filters['to'] ? Carbon::parse($filters['to'], 'UTC')->endOfDay() : null;
        }

        $records = AttendanceRecord::with(['user:id,name', 'location:id,name'])
            ->when($filters['user_id'], fn($q) => $q->where('user_id', $filters['user_id']))
            ->when($filters['location_id'], fn($q) => $q->where('location_id', $filters['location_id']))
            ->when($from, fn($q) => $q->whereDate('work_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('work_date', '<=', $to))
            ->orderByDesc('work_date')
            ->orderByDesc('clock_in')
            ->paginate(20)
            ->withQueryString();

        return view('admin.attendance.index', compact('users', 'locations', 'records', 'filters'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        $today = now('UTC')->toDateString();

        $hasTodayRecord = AttendanceRecord::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->exists();

        $records = AttendanceRecord::where('user_id', $user->id)
            ->orderByDesc('work_date')
            ->orderByDesc('clock_in')
            ->take(10)
            ->get();

        $locations = Location::orderBy('name')->get();

        return view('dashboard', compact('records', 'hasTodayRecord', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);
        [$clockIn, $clockOut] = $this->assembleTimes($data['work_date'], $data['clock_in'], $data['clock_out']);

        $exists = AttendanceRecord::where('user_id', $data['user_id'])
            ->whereDate('work_date', $data['work_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['work_date' => 'This user already has a record for this date.'])
                ->withInput();
        }

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
        $isAdmin = $request->user()->isAdmin();

        if ($isAdmin) {
            // Admin rules
            $rules = [
                'user_id' => ['required', Rule::exists('users', 'id')],
                'work_date' => ['required', 'date'],
                'clock_in' => ['nullable', 'date_format:H:i'],
                'clock_out' => ['nullable', 'date_format:H:i'],
                'break_time' => ['nullable', 'integer', 'min:0'],
                'location_id' => ['nullable', Rule::exists('locations', 'id')],
                'notes' => ['nullable', 'string', 'max:1000'],
            ];
        } else {
            // User rules
            $rules = [
                'clock_in' => ['required', 'date_format:H:i'],
                'clock_out' => ['required', 'date_format:H:i'],
                'break_time' => ['required', 'integer', 'min:0'],
                'location_id' => ['required', Rule::exists('locations', 'id')],
                'notes' => ['nullable', 'string', 'max:1000'],
            ];
        }

        $validated = $request->validate($rules);

        if (!$isAdmin) {
            $validated['user_id'] = $request->user()->id;
            $validated['work_date'] = now('UTC')->toDateString();
        }

        return $validated;
    }


    private function assembleTimes(string $date, ?string $in, ?string $out): array
    {
        $clockIn = $in ? Carbon::parse("$date $in", 'UTC')->setTimezone('UTC') : null;
        $clockOut = $out ? Carbon::parse("$date $out", 'UTC')->setTimezone('UTC') : null;
        return [$clockIn, $clockOut];
    }
}