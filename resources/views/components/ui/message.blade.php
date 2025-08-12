@props([
    'type' => 'success',
    'message' => null,
])

@php
    $containerClass = 'mb-4 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm';
    $iconClass = '';

    if ($type === 'success') {
        $containerClass .= ' border-green-200 bg-green-50 text-green-800';
        $iconClass = 'text-green-600';
    } elseif ($type === 'error') {
        $containerClass .= ' border-red-200 bg-red-50 text-red-800';
        $iconClass = 'text-red-600';
    }
@endphp

<div {{ $attributes->merge(['class' => $containerClass]) }}>
    <svg class="mt-0.5 h-5 w-5 flex-none {{ $iconClass }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16Zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586
                 7.707 9.293A1 1 0 106.293 10.707l2 2a1 1 0 001.414 0l4-4Z" clip-rule="evenodd" />
    </svg>
    <p class="font-medium">{{ $message }}</p>
</div>
