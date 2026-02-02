<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" x-ref="trigger"
        class="inline-flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:ring-4 focus:ring-slate-200 rounded-lg text-sm p-2 focus:outline-none transition"
        type="button">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
            <path
                d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
        </svg>
        <span class="sr-only">Aksi</span>
    </button>

    <template x-teleport="body">
        <div x-show="open" @click.away="open = false" x-transition x-anchor.bottom-end="$refs.trigger"
            class="fixed z-50 bg-white border border-slate-200 rounded-lg shadow-lg w-44">
            <ul class="p-2 text-sm text-slate-700 font-medium">
                {{-- @if ($edit)
                    <li>
                        <a href="{{ $edit }}"
                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Edit</a>
                    </li>
                @endif
                @if ($delete)
                    <li>
                        <a href="{{ $delete }}"
                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Hapus</a>
                    </li>
                @endif
                @if ($detail)
                    <li>
                        <a href="{{ $detail }}"
                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Detail</a>
                    </li>
                @endif --}}
                {{ $slot }}
            </ul>
        </div>
    </template>
</div>
