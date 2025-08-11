<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-800 leading-tight">
            Locations (Admin)
        </h2>
    </x-slot>

    <section class="px-4 py-6">
        <div class="mx-auto max-w-4xl">
            @if (session('success'))
                <div
                    class="mb-4 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <svg class="mt-0.5 h-5 w-5 flex-none text-green-600" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16Zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293A1 1 0 106.293 10.707l2 2a1 1 0 001.414 0l4-4Z"
                            clip-rule="evenodd" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Manage locations</h3>
                <a href="{{ route('admin.locations.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Add location
                </a>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <!-- Table for md+ -->
                <div class="hidden md:block">
                    <table class="w-full table-fixed">
                        <thead
                            class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-5 py-3">Name</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                            @forelse ($locations as $location)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3 align-middle">
                                        <span class="block truncate"
                                            title="{{ $location->name }}">{{ $location->name }}</span>
                                    </td>
                                    <td class="px-5 py-3 align-middle">
                                        @if ($location->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-xs font-medium text-green-800 bg-green-100">
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-xs font-medium text-slate-700 bg-slate-100">
                                                Paused
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 align-middle">
                                        <div class="flex items-center justify-end gap-4">
                                            <a href="{{ route('admin.locations.edit', $location) }}"
                                                class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-8.486 8.486a2 2 0 01-.878.506l-3.07.768a.75.75 0 01-.91-.91l.768-3.07a2 2 0 01.506-.878l8.486-8.486z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('admin.locations.update', $location) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="toggle" value="1">
                                                <button type="submit"
                                                    class="text-orange-600 hover:text-orange-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                                                    {{ $location->is_active ? 'Pause' : 'Resume' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10">
                                        <div class="flex flex-col items-center justify-center gap-3 text-center">
                                            <div class="rounded-full bg-slate-100 p-3">
                                                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm text-slate-600">No locations found.</p>
                                            <a href="{{ route('admin.locations.create') }}"
                                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                                                Create your first location
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Cards list for mobile -->
                <ul class="divide-y divide-slate-100 md:hidden">
                    @forelse ($locations as $location)
                        <li class="px-4 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-base font-medium text-slate-900"
                                        title="{{ $location->name }}">
                                        {{ $location->name }}
                                    </p>
                                    <div class="mt-1">
                                        @if ($location->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-xs font-medium text-green-800 bg-green-100">Active</span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-xs font-medium text-slate-700 bg-slate-100">Paused</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <a href="{{ route('admin.locations.edit', $location) }}"
                                        class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white p-2 text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-800"
                                        aria-label="Edit location">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-8.486 8.486a2 2 0 01-.878.506l-3.07.768a.75.75 0 01-.91-.91l.768-3.07a2 2 0 01.506-.878l8.486-8.486z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.locations.update', $location) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="toggle" value="1">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-md border border-orange-200 bg-white p-2 text-orange-600 shadow-sm hover:bg-orange-50"
                                            aria-label="{{ $location->is_active ? 'Pause' : 'Resume' }} location">
                                            @if ($location->is_active)
                                                <!-- Pause icon -->
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M6 4a1 1 0 011 1v10a1 1 0 11-2 0V5a1 1 0 011-1zm7 0a1 1 0 011 1v10a1 1 0 11-2 0V5a1 1 0 011-1z" />
                                                </svg>
                                            @else
                                                <!-- Play icon -->
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M6.5 5.5a1 1 0 011.53-.848l7 4.5a1 1 0 010 1.696l-7 4.5A1 1 0 016.5 14.5v-9z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-10">
                            <div class="flex flex-col items-center justify-center gap-3 text-center">
                                <div class="rounded-full bg-slate-100 p-3">
                                    <svg class="h-6 w-6 text-slate-400" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-600">No locations found.</p>
                                <a href="{{ route('admin.locations.create') }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                                    Create your first location
                                </a>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </section>
</x-admin-layout>
