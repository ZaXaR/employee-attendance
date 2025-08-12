<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-800 leading-tight">
            Job Roles (Admin)
        </h2>
    </x-slot>

    <section class="px-4 py-6">
        <div class="mx-auto max-w-4xl">
            {{-- Flash messages --}}
            @if (session('success'))
                <x-ui.message type="success" :message="session('success')" />
            @endif

            @if ($errors->any())
                <x-ui.message type="error" :message="$errors->first()" />
            @endif

            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Manage roles</h3>
                <a href="{{ route('admin.job-roles.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Add role
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
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                            @forelse($roles as $role)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3 align-middle">
                                        <span class="block truncate"
                                            title="{{ $role->name }}">{{ $role->name }}</span>
                                    </td>
                                    <td class="px-5 py-3 align-middle">
                                        <div class="flex items-center justify-end gap-4">
                                            <a href="{{ route('admin.job-roles.edit', $role) }}"
                                                class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-8.486 8.486a2 2 0 01-.878.506l-3.07.768a.75.75 0 01-.91-.91l.768-3.07a2 2 0 01.506-.878l8.486-8.486z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.job-roles.destroy', $role) }}"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
                                                    onclick="return confirm('Delete this role?')">
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                                        aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 100 2h.293l.853 10.242A2 2 0 007.14 18h5.72a2 2 0 001.994-1.758L15.707 6H16a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM8 8a1 1 0 112 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 10-2 0v6a1 1 0 102 0V8z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-5 py-10">
                                        <div class="flex flex-col items-center justify-center gap-3 text-center">
                                            <div class="rounded-full bg-slate-100 p-3">
                                                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm text-slate-600">No roles found.</p>
                                            <a href="{{ route('admin.job-roles.create') }}"
                                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                                                Create your first role
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
                    @forelse($roles as $role)
                        <li class="px-4 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-base font-medium text-slate-900"
                                        title="{{ $role->name }}">
                                        {{ $role->name }}
                                    </p>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <a href="{{ route('admin.job-roles.edit', $role) }}"
                                        class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white p-2 text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-800"
                                        aria-label="Edit role">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-8.486 8.486a2 2 0 01-.878.506l-3.07.768a.75.75 0 01-.91-.91l.768-3.07a2 2 0 01.506-.878l8.486-8.486z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.job-roles.destroy', $role) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-md border border-rose-200 bg-white p-2 text-rose-600 shadow-sm hover:bg-rose-50"
                                            aria-label="Delete role" onclick="return confirm('Delete this role?')">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                                aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 100 2h.293l.853 10.242A2 2 0 007.14 18h5.72a2 2 0 001.994-1.758L15.707 6H16a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM8 8a1 1 0 112 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 10-2 0v6a1 1 0 102 0V8z"
                                                    clip-rule="evenodd" />
                                            </svg>
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
                                <p class="text-sm text-slate-600">No roles found.</p>
                                <a href="{{ route('admin.job-roles.create') }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                                    Create your first role
                                </a>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </section>
</x-admin-layout>
