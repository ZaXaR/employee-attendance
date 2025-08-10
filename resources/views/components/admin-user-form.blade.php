@props(['action', 'method' => 'POST', 'submitLabel' => 'Save', 'user' => null, 'roles' => \App\Models\JobRole::all()])

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Name --}}
    <div class="flex flex-col gap-1">
        <label class="text-sm font-medium text-slate-600">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                      focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            required>
    </div>

    {{-- Email --}}
    <div class="flex flex-col gap-1">
        <label class="text-sm font-medium text-slate-600">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                  focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            required>
        @error('email')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Phone --}}
    <div class="flex flex-col gap-1">
        <label class="text-sm font-medium text-slate-600">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                      focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            placeholder="+380...">
    </div>

    {{-- Job Role (optional) --}}
    <div class="flex flex-col gap-1">
        <label for="job_role_id" class="text-sm font-medium text-slate-600">Job Role <span
                class="text-slate-400">(optional)</span></label>

        <select name="job_role_id" id="job_role_id"
            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
               focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="">— No Role —</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('job_role_id', $user->job_role_id ?? '') == $role->id)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        @error('job_role_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div class="flex flex-col gap-1">
        <label class="text-sm font-medium text-slate-600">Password</label>
        <input type="password" name="password"
            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                      focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            @if (!$user) required @endif>
    </div>

    {{-- Is Admin --}}
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_admin" id="is_admin" @checked(old('is_admin', $user->is_admin ?? false))
            class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
        <label for="is_admin" class="text-sm text-slate-700">Administrator</label>
    </div>

    {{-- Submit --}}
    <div>
        <button type="submit"
            class="inline-flex items-center bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm
                       hover:bg-blue-700 transition shadow-sm">
            {{ $submitLabel }}
        </button>
    </div>
</form>
