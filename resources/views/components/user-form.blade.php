<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none focus:ring-2"
            placeholder="Enter full name">
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none focus:ring-2"
            placeholder="Enter email">
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">
            {{ $method === 'POST' ? 'Password' : 'New Password (optional)' }}
        </label>
        <input id="password" type="password" name="password"
            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none focus:ring-2"
            placeholder="••••••••">
    </div>

    {{-- Role --}}
    <div class="flex items-center gap-3">
        <input type="checkbox" name="is_admin" id="is_admin" value="1"
            {{ isset($user) && $user->is_admin ? 'checked' : '' }}
            class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        <label for="is_admin" class="text-sm font-medium text-gray-700">Is admin</label>
    </div>

    {{-- Submit --}}
    <div>
        <button type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            {{ $submitLabel }}
        </button>
    </div>
</form>