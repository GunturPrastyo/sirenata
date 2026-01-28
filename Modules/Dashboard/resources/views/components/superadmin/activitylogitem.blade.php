@props([
    'title' => '',
    'description' => '',
    'time' => '',
    'user' => '',
])

<div class="flex gap-3 md:gap-4 p-3 md:p-4 border border-slate-300 rounded-lg hover:bg-gray-50 transition-colors">
    <div class="flex-shrink-0">
        <div class="h-8 w-8 md:h-10 md:w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fas fa-user text-sm md:text-base"></i>
        </div>
    </div>
    <div class="flex-1 min-w-0">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <p class="font-semibold text-gray-900 truncate">{{ $title }}
            </p>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded mt-1 md:mt-0">{{ $time }}
                lalu</span>
        </div>
        <p class="text-sm text-gray-600 mt-1 truncate">{{ $description }}.</p>
        <div class="flex items-center mt-2">
            <span class="text-xs text-gray-500">Oleh: <span class="font-medium">{{ $user }}</span></span>
        </div>
    </div>
</div>
