<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Add Location</h2>
            <a href="{{ route('admin.locations.index') }}"
               class="text-sm text-gray-600 hover:underline">← Back</a>
        </div>
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-xl mx-auto bg-white shadow rounded p-6">
            <form method="POST" action="{{ route('admin.locations.store') }}">
                @csrf

                {{-- Location name --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Location name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded bg-white text-gray-900
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Create Location
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>