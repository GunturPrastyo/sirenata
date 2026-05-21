@props([
    'name',
    'title' => 'Modal Title',
    'maxWidth' => 'sm:max-w-lg',
    'id' => null,
])

<div
    x-data="{
        show: false,
        modalId: '{{ $id ?? $name }}',
    }"
    x-show="show"
    x-on:open-modal.window="
        if ($event.detail === '{{ $name }}' || $event.detail === modalId) {
            show = true
        }
    "
    x-on:close-modal.window="
        if ($event.detail === '{{ $name }}' || $event.detail === modalId) {
            show = false
        }
    "
    x-on:keydown.escape.window="show = false"
    style="display: none"
    {{ $attributes->merge(['class' => '']) }}
>
    <template x-teleport="body">
        <div
            x-show="show"
            class="fixed top-0 left-0 z-[99] p-2 md:p-4 flex items-start justify-center w-screen h-screen overflow-y-auto"
            x-cloak
        >
            <!-- Backdrop -->
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="show = false"
                class="absolute inset-0 w-full h-full backdrop-blur-sm bg-white/70"
            ></div>

            <!-- Modal Content -->
            {{-- x-trap.inert.noscroll="show" --}}
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 -translate-y-2 sm:scale-95"
                class="relative px-7 py-6 w-full bg-white border shadow-lg border-neutral-200 {{ $maxWidth }} sm:rounded-lg"
            >
                <!-- Header -->
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-semibold">{{ $title }}</h3>
                    <button
                        @click="show = false"
                        class="flex absolute cursor-pointer top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50"
                    >
                        <svg
                            class="w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Body (Slot Content) -->
                <div class="relative w-full max-h-[80vh] overflow-y-auto">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                @if (isset($footer))
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:space-x-2">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
