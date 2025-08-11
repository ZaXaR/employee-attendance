<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Attendance
        </h2>
    </x-slot>

    <div class="py-10 px-4">
        <div class="mx-auto max-w-4xl space-y-8">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <x-attendance.user-form :locations="$locations" :hasTodayRecord="$hasTodayRecord" />

            {{-- Recent records --}}
            <x-attendance.user-records :records="$records" />

        </div>
    </div>
</x-app-layout>
