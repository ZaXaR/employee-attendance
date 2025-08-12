@props(['users', 'filters', 'locations'])

<details class="group border-t border-slate-200 pt-6">
    <summary class="cursor-pointer text-blue-600 hover:underline text-sm font-medium mb-4">
        + Add new attendance record
    </summary>

    <form method="POST" action="{{ route('admin.attendance.store') }}" class="grid grid-cols-1 sm:grid-cols-6 gap-6">
        @csrf

        {{-- User --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-slate-600 mb-1">User</label>
            <select name="user_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" @selected($filters['user_id'] == $u->id)>
                        {{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Work Date --}}
        <div>
            <label for="work_date" class="text-sm font-semibold text-slate-700 mb-1 block">Work Date (UTC)</label>
            <input type="text" name="work_date" id="work_date" value="{{ now()->utc()->format('Y-m-d') }}"
                class="datepicker w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                       focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder="YYYY-MM-DD">
        </div>

        {{-- Clock In --}}
        <x-time-input name="clock_in" :value="now()->utc()->format('H:i')" />

        {{-- Clock Out --}}
        <x-time-input name="clock_out" />

        {{-- Location --}}
        <div class="sm:col-span-2">
            <label for="location_id" class="text-sm font-medium text-slate-600 mb-1">Location</label>
            <select name="location_id" id="location_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                <option value="">—</option>
                @foreach ($locations as $loc)
                    @if ($loc->is_active)
                        <option value="{{ $loc->id }}" @selected($filters['location_id'] == $loc->id)>
                            {{ $loc->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        {{-- Break Time --}}
        <div>
            <label for="break_time" class="text-sm font-medium text-slate-600 mb-1">Break (minutes)</label>
            <input type="number" name="break_time" id="break_time" min="0"
                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                       focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder="e.g. 30">
        </div>

        {{-- Notes --}}
        <div class="sm:col-span-6">
            <label for="notes" class="text-sm font-medium text-slate-600 mb-1">Notes</label>
            <textarea name="notes" id="notes" rows="2"
                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                       focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder="Optional notes..."></textarea>
        </div>

        {{-- Submit --}}
        <div class="flex items-end sm:col-span-2 h-full">
            <button type="submit"
                class="inline-flex items-center bg-emerald-600 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-emerald-700 transition shadow-sm">
                Save
            </button>
        </div>
    </form>
</details>
