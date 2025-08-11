<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Attendance (Admin)
        </h2>
    </x-slot>

    <div class="py-10 px-4">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="space-y-6">
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

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                    <x-attendance.admin-filter :users="$users" :filters="$filters" />
                    <x-attendance.admin-form :users="$users" :filters="$filters" :locations="$locations" />
                </div>

                {{-- Records card --}}
                <x-attendance.admin-records :records="$records" :users="$users" />
            </div>
        </div>
    </div>
</x-admin-layout>
