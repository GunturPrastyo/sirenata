@props([
    'title' => '',
    'description' => '',
    'time' => '',
    'user' => '',
])

<div class="relative pl-6 pb-4 last:pb-0 border-l border-slate-200 ml-3">
    <!-- Timeline Dot -->
    <div class="absolute -left-[6px] top-1.5 w-3 h-3 rounded-full bg-indigo-600 border-2 border-white shadow-sm"></div>
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
        <h4 class="text-sm font-semibold text-gray-900 leading-none">{{ $title }}</h4>
        <span class="text-[10px] font-medium text-gray-500 bg-slate-100 px-2 py-0.5 rounded-full whitespace-nowrap">{{ $time }} lalu</span>
    </div>
    <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $description }}.</p>
    <div class="flex items-center gap-1.5 mt-1.5">
        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
        <span class="text-[10px] text-gray-400 font-medium">Oleh: <span class="font-semibold text-gray-600">{{ $user }}</span></span>
    </div>
</div>
