<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Locations</h2>
            <a href="{{ route('admin.locations.create') }}"
               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                + Add Location
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-3xl mx-auto bg-white shadow rounded p-6">
            @if(session('success'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <table class="w-full text-sm text-left border">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $location->name }}</td>
                            <td class="px-4 py-2">
                                @if($location->is_active)
                                    <span class="text-green-600 font-medium">Active</span>
                                @else
                                    <span class="text-slate-500">Paused</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.locations.edit', $location) }}"
                                   class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.locations.update', $location) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="toggle" value="1">
                                    <button type="submit" class="text-orange-600 hover:underline">
                                        {{ $location->is_active ? 'Pause' : 'Resume' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>