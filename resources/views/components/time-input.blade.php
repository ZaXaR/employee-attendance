@props(['name', 'label' => ucfirst($name), 'value' => '', 'placeholder' => 'HH:MM', 'id' => $name])

<div class="flex flex-col gap-1">
    <label for="{{ $id }}" class="text-sm font-semibold text-slate-700">
        {{ $label }} <span class="text-xs text-slate-400">(HH:MM, UTC)</span>
    </label>

    <div class="flex items-center gap-2">
        <input type="text" name="{{ $name }}" id="{{ $id }}" value="{{ old($name, $value) }}"
            class="timepicker w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm
                      focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                      placeholder:text-slate-400"
            placeholder="{{ $placeholder }}">

        @admin
            <button type="button" onclick="document.getElementById('{{ $id }}').value = ''"
                class="text-slate-400 hover:text-red-500 px-2 text-lg font-bold leading-none" title="Clear">×</button>
        @endadmin
    </div>
</div>
