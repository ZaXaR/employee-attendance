<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">Edit user</h2>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200">
                ← Back
            </a>
        </div>
    </x-slot>


    <div class="py-10 px-4">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
            <x-user-form :action="route('admin.users.update', $user)" method="PUT" submit-label="Update user" :user="$user" />
        </div>
    </div>
</x-admin-layout>
