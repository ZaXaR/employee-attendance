@props(['id', 'user', 'text'])

<div x-data="{ visible: false }" x-show="visible" x-cloak x-init="// Open only when the event's id matches this record
window.addEventListener('open-note', e => { visible = e.detail === {{ $id }} });" @keydown.escape.window="visible = false"
    @click.self="visible = false" role="dialog" aria-modal="true"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="w-full max-w-md mx-auto bg-white rounded-xl shadow-xl p-6" role="document">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">
            Note from {{ $user }}
        </h2>

        <div class="text-sm text-slate-700 whitespace-pre-line max-h-60 overflow-auto">
            {{ $text }}
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="button" class="px-3 py-1.5 text-sm rounded bg-slate-700 text-white hover:bg-slate-800"
                @click="visible = false">
                Close
            </button>
        </div>
    </div>
</div>
