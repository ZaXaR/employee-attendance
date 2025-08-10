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
                            <input type="date" name="from" value="{{ $filters['from'] }}"
                                class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-600 mb-1">To</label>
                            <input type="date" name="to" value="{{ $filters['to'] }}"
                                class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200">
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

                            {{-- Date --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Work Date (UTC)</label>
                                <input type="date" name="work_date" required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>

                            {{-- Clock in --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Clock In (HH:MM)</label>
                                <input type="time" name="clock_in"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>

                            {{-- Clock out --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Clock Out (HH:MM)</label>
                                <input type="time" name="clock_out"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>

                            {{-- Submit --}}
                            <div class="flex items-end">
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

                    <div class="overflow-x-auto">
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
                                            <details class="group">
                                                <summary
                                                    class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-1.5 text-slate-800 ring-1 ring-inset ring-slate-200 hover:bg-slate-200 cursor-pointer">
                                                    Edit
                                                </summary>
                                                <div
                                                    class="mt-3 w-[640px] max-w-full rounded-lg border border-slate-200 bg-white p-4 shadow-sm">

                                                    <form method="POST"
                                                        action="{{ route('admin.attendance.update', $r) }}"
                                                        class="grid grid-cols-1 sm:grid-cols-6 gap-3">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="sm:col-span-2">
                                                            <label
                                                                class="block text-sm text-slate-600 mb-1">User</label>
                                                            <select name="user_id"
                                                                class="w-full rounded-lg border-slate-300">
                                                                @foreach ($users as $u)
                                                                    <option value="{{ $u->id }}"
                                                                        @selected($u->id == $r->user_id)>
                                                                        {{ $u->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-slate-600 mb-1">Date
                                                                (UTC)
                                                            </label>
                                                            <input type="date" name="work_date"
                                                                value="{{ optional($r->work_date)->toDateString() }}"
                                                                class="w-full rounded-lg border-slate-300" required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-slate-600 mb-1">Clock
                                                                in</label>
                                                            <input type="time" name="clock_in"
                                                                value="{{ optional($r->clock_in)->format('H:i') }}"
                                                                class="w-full rounded-lg border-slate-300">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-slate-600 mb-1">Clock
                                                                out</label>
                                                            <input type="time" name="clock_out"
                                                                value="{{ optional($r->clock_out)->format('H:i') }}"
                                                                class="w-full rounded-lg border-slate-300">
                                                        </div>
                                                        <div class="flex items-end gap-2">
                                                            <button type="submit"
                                                                class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700 transition">
                                                                Save
                                                            </button>
                                                            <form method="POST"
                                                                action="{{ route('admin.attendance.destroy', $r) }}"
                                                                onsubmit="return confirm('Delete this record?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-white hover:bg-red-700 transition">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </form>
                                                </div>
                                            </details>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-5 py-6 text-slate-500" colspan="6">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
