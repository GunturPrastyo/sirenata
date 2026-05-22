@props(['title', 'count', 'growth', 'period'])


<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-5 card-hover">
    <div class="flex items-center justify-between mb-3 md:mb-4">
        <div>
            <p class="text-sm text-gray-500 font-medium">{{ $title }}</p>
            <h3 class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">{{ $count }}</h3>
        </div>
        {{ $slot }}
    </div>
    <div class="pt-3 border-t border-slate-300">
        <div class="flex items-center text-xs md:text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1 text-xs"></i> {{ $growth }}
            </span>
            <span class="text-gray-400 mx-2">•</span>
            <span class="text-gray-500">{{ $period }}</span>
        </div>
    </div>
</div>
