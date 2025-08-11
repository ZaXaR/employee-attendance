@props(['records', 'users'])

<div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full table-fixed text-sm text-left">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
            <tr>
                <th class="px-6 py-3 w-40">User</th>
                <th class="px-6 py-3 w-28">Date</th>
                <th class="px-6 py-3 w-24">Clock In</th>
                <th class="px-6 py-3 w-24">Clock Out</th>
                <th class="px-6 py-3 w-24">Worked</th>
                <th class="px-6 py-3 w-24">Break</th>
                <th class="px-6 py-3 w-36">Location</th>
                <th class="px-6 py-3 w-24">Notes</th>
                <th class="px-6 py-3 w-24">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($records as $r)
                <tr class="odd:bg-white even:bg-gray-50 align-top">
                    <td class="px-6 py-4 text-gray-900">
                        <span class="block truncate" title="{{ $r->user->name }}">{{ $r->user->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        {{ $r->work_date?->format('Y-m-d') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        {{ $r->clock_in?->format('H:i') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        {{ $r->clock_out?->format('H:i') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        {{ $r->formatted_work_time }}
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        {{ $r->formatted_break }}
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        <span class="block truncate" title="{{ $r->location?->name ?? '—' }}">
                            {{ $r->location?->name ?? '—' }}
                        </span>
                    </td>

                    {{-- Notes (open fixed modal) --}}
                    <td class="px-6 py-4 text-gray-700">
                        @if ($r->notes)
                            <button
                                type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-note', { detail: {{ $r->id }} }))"
                                class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded bg-amber-100 text-amber-800 hover:bg-amber-200"
                                title="View note"
                            >
                                Note
                            </button>
                        @else
                            —
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-attendance-{{ $r->id }}' }))"
                            class="text-blue-600 hover:underline text-sm">
                            Edit
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-6 text-center text-gray-500">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Render edit modals --}}
    @foreach ($records as $r)
        <x-admin-attendance-edit :record="$r" :users="$users" />
    @endforeach

    {{-- Render note modals (fixed, outside table flow) --}}
    @foreach ($records as $r)
        @if ($r->notes)
            <x-ui.note-modal
                :id="$r->id"
                :user="$r->user->name"
                :text="$r->notes"
            />
        @endif
    @endforeach
</div>