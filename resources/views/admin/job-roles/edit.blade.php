<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-slate-800">Edit Job Role</h2>
            <a href="{{ route('admin.job-roles.index') }}"
                class="inline-flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-slate-800 hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                ← Back to list
            </a>
        </div>
    </x-slot>

    <section class="px-4 py-6">
        <div class="mx-auto max-w-xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.job-roles.update', $jobRole) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Role name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $jobRole->name) }}"
                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Enter role name">
                    @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.job-roles.index') }}"
                        class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-slate-800 hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5 4a1 1 0 000 2h10a1 1 0 100-2H5zM4 9a1 1 0 011-1h10a1 1 0 011 1v7a2 2 0 01-2 2H6a2 2 0 01-2-2V9z" />
                        </svg>
                        Update role
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
