@props([
    'locations' => [],
    'hasTodayRecord' => false,
])

@if (!$hasTodayRecord)
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Create attendance for today</h3>

        <form method="POST" action="{{ route('attendance.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @csrf

            {{-- Clock In --}}
            <x-time-input name="clock_in" :value="old('clock_in', now()->utc()->format('H:i'))" />

            {{-- Clock Out --}}
            <x-time-input name="clock_out" :value="old('clock_out')" />

            {{-- Break Time --}}
            <div>
                <label for="break_time" class="text-sm font-medium text-slate-600 mb-1">Break (minutes)</label>
                <input type="number" name="break_time" id="break_time" min="0" value="{{ old('break_time') }}"
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" placeholder="e.g. 30">
            </div>

            {{-- Location --}}
            <div>
                <label for="location_id" class="text-sm font-medium text-slate-600 mb-1">Location</label>
                <select name="location_id" id="location_id"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                    <option value="">—</option>
                    @foreach ($locations as $loc)
                        <option value="{{ $loc->id }}" @selected(old('location_id') == $loc->id)>
                            {{ $loc->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Notes --}}
            <div class="sm:col-span-2">
                <label for="notes" class="text-sm font-medium text-slate-600 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="2"
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" placeholder="Optional notes...">{{ old('notes') }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="sm:col-span-2 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-blue-700 transition shadow-sm">
                    Save record
                </button>
            </div>
        </form>
    </div>
@else
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-slate-700">
        ✅ You already have a record for today ({{ now('UTC')->toDateString() }} UTC).
    </div>
@endif
