<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Attendance
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
                @if (session('error'))
                    <div class="rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Action toolbar --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-700">
                                🕒
                            </div>
                            <div>
                                <div class="text-sm text-slate-500">Today</div>
                                <div class="text-base font-semibold text-slate-800">
                                    {{ now('UTC')->toDateString() }} (UTC)
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @if (!$hasClockIn)
                                <span
                                    class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-sm font-medium text-amber-800 ring-1 ring-inset ring-amber-200">
                                    Status: not checked in
                                </span>
                            @elseif (!$hasClockOut)
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 ring-1 ring-inset ring-blue-200">
                                    Status: checked in
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 ring-1 ring-inset ring-emerald-200">
                                    Status: checked out
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            @if (!$hasClockIn)
                                <form method="POST" action="{{ route('attendance.clock-in') }}">
                                    @csrf
                                    <button type="submit"
                                        onclick="this.disabled=true; this.classList.add('opacity-70','cursor-not-allowed'); this.innerText='Submitting...'; this.form.submit();"
                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-white shadow-sm ring-1 ring-inset ring-blue-600/20 hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 transition">
                                        <span>Clock in</span>
                                    </button>
                                </form>
                            @elseif (!$hasClockOut)
                                <form method="POST" action="{{ route('attendance.clock-out') }}">
                                    @csrf
                                    <button type="submit"
                                        onclick="this.disabled=true; this.classList.add('opacity-70','cursor-not-allowed'); this.innerText='Submitting...'; this.form.submit();"
                                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-white shadow-sm ring-1 ring-inset ring-emerald-600/20 hover:bg-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 transition">
                                        <span>Clock out</span>
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-slate-500">All set for today ✅</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Records card --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-3.5">
                        <h3 class="text-base font-semibold text-slate-800">Recent records</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
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
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($records as $r)
                                    <tr class="odd:bg-white even:bg-slate-50">
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
                                                    $endTime = $r->clock_out ?? now();
                                                    $duration = $endTime->diffAsCarbonInterval($r->clock_in);
                                                    $hours = $duration->hours;
                                                    $minutes = $duration->minutes;
                                                @endphp
                                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }} min
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-5 py-6 text-slate-500" colspan="4">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> {{-- /space-y --}}
        </div>
    </div>
</x-app-layout>
