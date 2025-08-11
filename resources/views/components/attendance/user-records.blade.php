@props(['records' => []])

<div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-3.5">
        <h3 class="text-base font-semibold text-slate-800">Your recent records</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Date</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Clock in</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Clock out</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Break</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Duration</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Location</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($records as $r)
                    <tr class="odd:bg-white even:bg-slate-50">
                        <td class="px-5 py-3 text-slate-900">{{ optional($r->work_date)->toDateString() }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ optional($r->clock_in)->format('H:i') ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ optional($r->clock_out)->format('H:i') ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ $r->break_time ?? 0 }} min</td>
                        <td class="px-5 py-3 text-slate-900">
                            @if ($r->clock_in)
                                @php
                                    $endTime = $r->clock_out ?? now();
                                    $duration = $endTime->diffAsCarbonInterval($r->clock_in);
                                    $totalMinutes = $duration->totalMinutes - ($r->break_time ?? 0);
                                    $hours = floor($totalMinutes / 60);
                                    $minutes = $totalMinutes % 60;
                                @endphp
                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }} min
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-900">
                            {{ optional($r->location)->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-slate-900">
                            @if ($r->notes)
                                <button
                                    type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('open-note', { detail: {{ $r->id }} }))"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded bg-amber-100 text-amber-800 hover:bg-amber-200"
                                >
                                    Note
                                </button>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-5 py-6 text-slate-500 text-center" colspan="7">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Note modals --}}
    @foreach ($records as $r)
        @if ($r->notes)
            <x-ui.note-modal
                :id="$r->id"
                :user="auth()->user()?->name ?? 'User'"
                :text="$r->notes"
            />
        @endif
    @endforeach
</div>