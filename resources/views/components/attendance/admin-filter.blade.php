@props(['users', 'filters', 'locations'])

<form method="GET" class="grid grid-cols-1 gap-6 md:grid-cols-5">
    {{-- User select --}}
    <div class="flex flex-col">
        <label class="text-sm font-medium text-slate-600 mb-1">User</label>
        <select name="user_id"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200">
            <option value="">All users</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected($filters['user_id'] == $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Location select --}}
    <div class="flex flex-col">
        <label class="text-sm font-medium text-slate-600 mb-1">Location</label>
        <select name="location_id"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200">
            <option value="">All locations</option>
            @foreach ($locations as $loc)
                <option value="{{ $loc->id }}" @selected($filters['location_id'] == $loc->id)>{{ $loc->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Date filters --}}
    <div class="flex flex-col">
        <label class="text-sm font-medium text-slate-600 mb-1">From</label>
        <input type="text" name="from" value="{{ $filters['from'] }}"
            class="datepicker rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200"
            placeholder="YYYY-MM-DD">
    </div>

    <div class="flex flex-col">
        <label class="text-sm font-medium text-slate-600 mb-1">To</label>
        <input type="text" name="to" value="{{ $filters['to'] }}"
            class="datepicker rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:ring focus:ring-blue-200"
            placeholder="YYYY-MM-DD">
    </div>

    {{-- Filter actions --}}
    <div class="flex gap-2 items-end">
        <button type="submit"
            class="inline-flex items-center bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-slate-900 transition shadow-sm">
            Apply
        </button>
        <a href="{{ route('admin.attendance.index') }}"
            class="inline-flex items-center bg-slate-100 text-slate-700 px-4 py-2.5 rounded-lg text-sm hover:bg-slate-200 transition shadow-sm">
            Reset
        </a>
    </div>
</form>