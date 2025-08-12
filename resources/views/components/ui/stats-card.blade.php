@props(['label', 'value'])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-slate-200 bg-white p-4']) }}>
    <div class="text-sm text-slate-500">{{ $label }}</div>
    <div class="mt-1 text-2xl font-semibold text-slate-800">{{ $value }}</div>
</div>
