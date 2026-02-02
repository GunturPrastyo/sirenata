@props([
    'id',
    'title' => 'Delete Confirmation',
    'message' => 'Are you sure you want to delete this item?',
    'route',
    'itemName' => null,
    'buttonText' => 'Delete',
    'buttonClass' =>
        'text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer',
])

<div x-data="{ open: false }" class="inline-block">
    <!-- Trigger Button -->
    <button type="button" @click="open = true" class="{{ $buttonClass }} ">
        {{ $buttonText }}
    </button>

    <!-- Modal -->
    <template x-teleport="body">
        <template x-if="open">
            <div class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="open = false">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>

                <!-- Modal Content -->
                <div id="{{ $id }}" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                    class="relative w-full max-w-md p-4 md:p-6 bg-neutral-primary-soft border border-default rounded-base shadow-sm">
                    <!-- Close Button -->
                    <button type="button" @click="open = false"
                        class="absolute top-3 cursor-pointer end-2.5 text-body hover:bg-neutral-tertiary rounded-base w-9 h-9 inline-flex items-center justify-center">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Content -->
                    <div class="text-center p-4 md:p-5">
                        <svg class="mx-auto mb-4 w-12 h-12 text-fg-disabled" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                        <h3 class="mb-6 text-body">
                            {!! $message !!}
                            @if ($itemName)
                                <strong>{{ $itemName }}</strong>?
                            @endif
                        </h3>

                        <form action="{{ $route }}" method="POST" class="flex justify-center space-x-4">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-white cursor-pointer bg-danger hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium rounded-base px-4 py-2.5 text-sm font-medium">
                                Yes, I'm sure
                            </button>

                            <button type="button" @click="open = false"
                                class="text-body cursor-pointer bg-neutral-secondary-medium hover:bg-neutral-tertiary-medium rounded-base px-4 py-2.5 text-sm font-medium">
                                No, cancel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </template>
</div>
