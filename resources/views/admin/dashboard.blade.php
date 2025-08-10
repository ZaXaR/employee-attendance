<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin dashboard
        </h2>
    </x-slot>

    <div class="py-10 px-4">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

            {{-- Stats cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <div class="text-sm text-slate-500">Total users</div>
                    <div class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['total'] }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <div class="text-sm text-slate-500">Admins</div>
                    <div class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['admins'] }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <div class="text-sm text-slate-500">Members</div>
                    <div class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['members'] }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <div class="text-sm text-slate-500">New last 7 days</div>
                    <div class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['new_last_7'] }}</div>
                </div>
            </div>

            {{-- Users table --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-3.5">
                    <h3 class="text-base font-semibold text-slate-800">Users</h3>
                    <a href="{{ route('admin.users.create') }}"
                       class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">
                        Create user
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Name</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Email</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Role</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Created</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($users as $user)
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td class="px-5 py-3 text-slate-900">{{ $user->id }}</td>
                                    <td class="px-5 py-3 text-slate-900">{{ $user->name }}</td>
                                    <td class="px-5 py-3 text-slate-900">{{ $user->email }}</td>
                                    <td class="px-5 py-3">
                                        @if($user->is_admin)
                                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 ring-1 ring-inset ring-purple-200">
                                                Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-200">
                                                Member
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-900">{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="rounded-lg bg-amber-500 px-3 py-1.5 text-white hover:bg-amber-600">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-lg bg-red-600 px-3 py-1.5 text-white hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-5 py-6 text-slate-500" colspan="6">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-4">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>