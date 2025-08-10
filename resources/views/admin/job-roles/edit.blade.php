<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Job Role</h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-md mx-auto bg-white shadow rounded p-6">
            <form method="POST" action="{{ route('admin.job-roles.update', $jobRole) }}">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $jobRole->name) }}"
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.job-roles.index') }}"
                       class="mr-3 text-sm text-gray-600 hover:underline">Cancel</a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>