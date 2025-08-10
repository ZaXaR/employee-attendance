@props(['record'])

<div x-show="$store.attendanceModal === {{ $record->id }}" x-transition x-cloak
    class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center px-4">
    <div @click.outside="$store.attendanceModal = null"
        class="bg-white w-full max-w-xl p-6 sm:p-8 rounded-xl shadow-lg relative transition-all">

        {{-- Закрытие --}}
        <button @click="$store.attendanceModal = null"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>

        {{-- Заголовок --}}
        <h2 class="text-xl font-semibold text-slate-900 mb-6">Edit Attendance</h2>

        {{-- Основная форма обновления (получает свой id) --}}
        <form id="attendance-update-{{ $record->id }}" method="POST"
            action="{{ route('admin.attendance.update', $record) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- Имя пользователя (только отображение) --}}
            <div>
                <label class="text-sm text-slate-500 mb-1 block">User</label>
                <div class="text-base font-medium text-slate-800">{{ $record->user->name }}</div>
                <input type="hidden" name="user_id" value="{{ $record->user_id }}">
            </div>

            {{-- Дата --}}
            <div>
                <label class="text-sm text-slate-500 mb-1 block">Date</label>
                <input type="date" name="work_date" value="{{ optional($record->work_date)->format('Y-m-d') }}"
                    class="w-full px-3 py-2 rounded-md border border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 text-slate-800 text-sm"
                    required>
            </div>

            {{-- Время входа / выхода --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-slate-500 mb-1 block">Clock In (UTC)</label>
                    <input type="time" name="clock_in" value="{{ optional($record->clock_in)->format('H:i') }}"
                        class="w-full px-3 py-2 rounded-md border border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 text-slate-800 text-sm"
                        required>
                </div>
                <div>
                    <label class="text-sm text-slate-500 mb-1 block">Clock Out (UTC)</label>
                    <input type="time" name="clock_out" value="{{ optional($record->clock_out)->format('H:i') }}"
                        class="w-full px-3 py-2 rounded-md border border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 text-slate-800 text-sm">
                </div>
            </div>
        </form>

        {{-- Действия (кнопки рядом, без вложенных форм) --}}
        <div class="flex justify-end gap-3 pt-4">
            {{-- Кнопка Save отправляет форму по id через атрибут form --}}
            <button type="submit" form="attendance-update-{{ $record->id }}"
                class="bg-emerald-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-emerald-700 transition">
                Save
            </button>

            {{-- Отдельная форма удаления --}}
            <form method="POST" action="{{ route('admin.attendance.destroy', $record) }}"
                onsubmit="return confirm('Delete this record?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
