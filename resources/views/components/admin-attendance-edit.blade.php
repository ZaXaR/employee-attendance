@props(['record', 'users'])

<x-modal name="edit-attendance-{{ $record->id }}">
    <div class="px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Edit Attendance</h2>

        <form id="attendance-update-{{ $record->id }}" method="POST"
            action="{{ route('admin.attendance.update', $record) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- User --}}
            <div>
                <label class="text-sm text-gray-500 mb-1 block">User</label>
                <div class="text-base font-medium text-gray-800">{{ $record->user->name }}</div>
                <input type="hidden" name="user_id" value="{{ $record->user_id }}">
            </div>

            {{-- Work date --}}
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Date</label>
                <input type="text" name="work_date" value="{{ $record->work_date?->format('Y-m-d') }}"
                    class="datepicker w-full rounded border border-gray-300 px-3 py-2 text-sm text-gray-800 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50"
                    placeholder="YYYY-MM-DD" required>
            </div>

            {{-- Clock In / Out --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500 mb-1 block">Clock In (UTC)</label>
                    <div class="flex items-center gap-2">
                        <input type="text" name="clock_in" value="{{ $record->clock_in?->format('H:i') }}"
                            class="timepicker w-full rounded border border-gray-300 px-3 py-2 text-sm text-gray-800 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50"
                            placeholder="HH:MM">
@admin
    <button type="button"
            onclick="this.previousElementSibling.value = ''"
            class="text-slate-400 hover:text-red-500 px-2 text-lg font-bold leading-none"
            title="Clear">×</button>
@endadmin

                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-500 mb-1 block">Clock Out (UTC)</label>
                    <div class="flex items-center gap-2">
                        <input type="text" name="clock_out" value="{{ $record->clock_out?->format('H:i') }}"
                            class="timepicker w-full rounded border border-gray-300 px-3 py-2 text-sm text-gray-800 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50"
                            placeholder="HH:MM">
@admin
    <button type="button"
            onclick="this.previousElementSibling.value = ''"
            class="text-slate-400 hover:text-red-500 px-2 text-lg font-bold leading-none"
            title="Clear">×</button>
@endadmin

                    </div>
                </div>
            </div>

            {{-- Break time --}}
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Break Time (minutes)</label>
                <input type="number" name="break_time" value="{{ $record->break_time }}"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm text-gray-800 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50"
                    min="0">
            </div>

            {{-- Location --}}
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Location</label>
                <select name="location_id"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm text-gray-800 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50">
                    <option value="">—</option>
                    @foreach (\App\Models\Location::where('is_active', true)->orderBy('name')->get() as $location)
                        <option value="{{ $location->id }}" @selected($record->location_id === $location->id)>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Notes --}}
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Notes</label>
                <textarea name="notes" rows="3"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm text-gray-800 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50">{{ $record->notes }}</textarea>
            </div>
        </form>

        {{-- Action buttons --}}
        <div class="flex justify-end gap-3 pt-4">
            <button type="submit" form="attendance-update-{{ $record->id }}"
                class="bg-emerald-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-emerald-700 transition">
                Save
            </button>

            <form method="POST" action="{{ route('admin.attendance.destroy', $record) }}"
                onsubmit="return confirm('Delete this record?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</x-modal>
