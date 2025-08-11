@props(['records', 'users'])

<div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
            <tr>
                <th class="px-6 py-3">User</th>
                <th class="px-6 py-3">Date</th>
                <th class="px-6 py-3">Clock In</th>
                <th class="px-6 py-3">Clock Out</th>
                <th class="px-6 py-3">Worked</th>
                <th class="px-6 py-3">Break</th>
                <th class="px-6 py-3">Location</th>
                <th class="px-6 py-3">Notes</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($records as $r)
                <tr class="odd:bg-white even:bg-gray-50 align-top">
                    <td class="px-6 py-4 text-gray-900">{{ $r->user->name }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $r->work_date?->format('Y-m-d') ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $r->clock_in?->format('H:i') ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $r->clock_out?->format('H:i') ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $r->formatted_work_time }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $r->formatted_break }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $r->location?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-700 whitespace-pre-line">{{ $r->notes ?? '—' }}</td>
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

    {{-- Render modals --}}
    @foreach ($records as $r)
        <x-admin-attendance-edit :record="$r" :users="$users" />
    @endforeach
</div>
