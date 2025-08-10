<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Attendance (Admin)
        </h2>
    </x-slot>

    <div class="py-10 px-4">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="space-y-6">
                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                    {{-- Filter toolbar --}}
                    <form method="GET" class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        {{-- User select --}}
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-600 mb-1">User</label>
                            <select name="user_id"
                                class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">All users</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" @selected($filters['user_id'] == $u->id)>{{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date filters --}}
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-600 mb-1">From</label>
                            <input type="text" name="from" value="{{ $filters['from'] }}"
                                class="datepicker rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200"
                                placeholder="YYYY-MM-DD">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-600 mb-1">To</label>
                            <input type="text" name="to" value="{{ $filters['to'] }}"
                                class="datepicker rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200"
                                placeholder="YYYY-MM-DD">
                        </div>

                        {{-- Filter actions --}}
                        <div class="flex gap-2 items-end">
                            <button type="submit"
                                class="inline-flex items-center bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-slate-900 transition shadow-sm">
                                Apply
                            </button>
                            <a href="{{ route('admin.attendance.index') }}"
                                class="inline-flex items-center bg-slate-100 text-slate-700 px-4 py-2.5 rounded-lg text-sm hover:bg-slate-200 transition shadow-sm">
                                Reset
                            </a>
                        </div>
                    </form>

                    {{-- New record: collapsible --}}
                    <details class="group border-t border-slate-200 pt-6">
                        <summary class="cursor-pointer text-blue-600 hover:underline text-sm font-medium mb-4">
                            + Add new attendance record
                        </summary>

                        <form method="POST" action="{{ route('admin.attendance.store') }}"
                            class="grid grid-cols-1 sm:grid-cols-5 gap-6">
                            @csrf

                            {{-- User --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">User</label>
                                <select name="user_id" required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}" @selected($filters['user_id'] == $u->id)>
                                            {{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Work Date --}}
                            <div>
                                <label for="work_date" class="text-sm font-semibold text-slate-700 mb-1 block">Work Date
                                    (UTC)</label>
                                <input type="text" name="work_date" id="work_date"
                                    value="{{ now()->utc()->format('Y-m-d') }}"
                                    class="datepicker w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
        focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="YYYY-MM-DD">
                            </div>

                            {{-- Clock In --}}
                            <div class="flex flex-col gap-1 sm:col-span-1">
                                <label for="clock_in" class="text-sm font-semibold text-slate-700">
                                    Clock In <span class="text-xs text-slate-400">(HH:MM, UTC)</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="text" name="clock_in" id="clock_in"
                                        value="{{ now()->utc()->format('H:i') }}"
                                        class="timepicker w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                  focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                  placeholder:text-slate-400"
                                        placeholder="HH:MM">
                                    <button type="button" onclick="document.getElementById('clock_in').value = ''"
                                        class="text-slate-400 hover:text-red-500 px-2 text-lg font-bold leading-none"
                                        title="Clear">
                                        ×
                                    </button>
                                </div>
                            </div>

                            {{-- Clock Out --}}
                            <div class="flex flex-col gap-1 sm:col-span-1">
                                <label for="clock_out" class="text-sm font-medium text-slate-600">Clock Out
                                    (HH:MM)</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" name="clock_out" id="clock_out"
                                        class="timepicker w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                  focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                  placeholder:text-slate-400"
                                        placeholder="HH:MM">
                                    <button type="button" onclick="document.getElementById('clock_out').value = ''"
                                        class="text-slate-400 hover:text-red-500 px-2 text-lg font-bold leading-none"
                                        title="Clear">
                                        ×
                                    </button>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="flex items-end sm:col-span-1 h-full">
                                <button type="submit"
                                    class="inline-flex items-center bg-emerald-600 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-emerald-700 transition shadow-sm">
                                    Save
                                </button>
                            </div>
                        </form>
                    </details>
                </div>

                {{-- Records card --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-3.5">
                        <h3 class="text-base font-semibold text-slate-800">Records</h3>
                    </div>

                    <div x-data="{ openId: null }" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                        User</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                        Date</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                        Clock in (UTC)</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                        Clock out (UTC)</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                        Duration</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($records as $r)
                                    <tr class="odd:bg-white even:bg-slate-50 align-top">
                                        <td class="px-5 py-3 text-slate-900">
                                            {{ $r->user->name }}
                                        </td>
                                        <td class="px-5 py-3 text-slate-900">
                                            {{ optional($r->work_date)->toDateString() }}
                                        </td>
                                        <td class="px-5 py-3 text-slate-900">
                                            {{ optional($r->clock_in)->format('H:i') ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 text-slate-900">
                                            {{ optional($r->clock_out)->format('H:i') ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 text-slate-900">
                                            @if ($r->clock_in)
                                                @php
                                                    $endTime = $r->clock_out ?? now('UTC');
                                                    $duration = $endTime->diffAsCarbonInterval($r->clock_in);
                                                    $hours = $duration->hours + $duration->days * 24;
                                                    $minutes = $duration->minutes;
                                                @endphp
                                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }} min
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-slate-900">
                                            <button @click="$store.attendanceModal = {{ $r->id }}"
                                                class="text-sm text-blue-600 hover:underline">Edit</button>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-5 py-6 text-slate-500" colspan="6">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @foreach ($records as $r)
                            <x-admin-attendance-edit :record="$r" :users="$users" />
                        @endforeach


                    </div>

                    @if ($records->hasPages())
                        <div class="px-5 py-4 border-t border-slate-200">
                            {{ $records->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
