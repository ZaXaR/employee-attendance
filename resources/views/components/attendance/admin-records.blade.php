@props(['records', 'users'])

<div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-3.5">
        <h3 class="text-base font-semibold text-slate-800">Attendance Records</h3>
    </div>

    {{-- Desktop table --}}
    <div class="overflow-x-auto hidden md:block">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    @foreach (['User', 'Date', 'Clock In', 'Clock Out', 'Worked', 'Break', 'Location', 'Notes', 'Actions'] as $heading)
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            {{ $heading }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($records as $r)
                    <tr class="odd:bg-white even:bg-slate-50 align-top">
                        <td class="px-5 py-3 text-slate-900 truncate" title="{{ $r->user->name }}">{{ $r->user->name }}
                        </td>
                        <td class="px-5 py-3 text-slate-900">{{ $r->work_date?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ $r->clock_in?->format('H:i') ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ $r->clock_out?->format('H:i') ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ $r->formatted_work_time }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ $r->formatted_break }}</td>
                        <td class="px-5 py-3 text-slate-900 truncate" title="{{ $r->location?->name ?? '—' }}">
                            {{ $r->location?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-900">
                            @if ($r->notes)
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('open-note', { detail: {{ $r->id }} }))"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded bg-amber-100 text-amber-800 hover:bg-amber-200"
                                    title="View note">
                                    Note
                                </button>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-attendance-{{ $r->id }}' }))"
                                class="text-blue-600 hover:underline text-sm">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-6 text-center text-slate-500">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden divide-y divide-slate-100">
        @forelse ($records as $r)
            <div class="px-5 py-4 text-sm text-slate-700 space-y-2">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-slate-800">{{ $r->user->name }}</span>
                    <span class="text-xs text-slate-500">{{ $r->work_date?->format('Y-m-d') ?? '—' }}</span>
                </div>
                <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                    <div><span class="font-semibold">In:</span> {{ $r->clock_in?->format('H:i') ?? '—' }}</div>
                    <div><span class="font-semibold">Out:</span> {{ $r->clock_out?->format('H:i') ?? '—' }}</div>
                    <div><span class="font-semibold">Worked:</span> {{ $r->formatted_work_time }}</div>
                    <div><span class="font-semibold">Break:</span> {{ $r->formatted_break }}</div>
                    <div class="col-span-2"><span class="font-semibold">Location:</span>
                        {{ $r->location?->name ?? '—' }}</div>
                </div>
                <div class="flex gap-2 pt-2">
                    @if ($r->notes)
                        <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-note', { detail: {{ $r->id }} }))"
                            class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded bg-amber-100 text-amber-800 hover:bg-amber-200">
                            Note
                        </button>
                    @endif
                    <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-attendance-{{ $r->id }}' }))"
                        class="text-blue-600 hover:underline text-xs">
                        Edit
                    </button>
                </div>
            </div>
        @empty
            <div class="px-5 py-6 text-center text-slate-500">No records found.</div>
        @endforelse
    </div>

    {{-- Modals --}}
    @foreach ($records as $r)
        <x-admin-attendance-edit :record="$r" :users="$users" />
        @if ($r->notes)
            <x-ui.note-modal :id="$r->id" :user="$r->user->name" :text="$r->notes" />
        @endif
    @endforeach
</div>
