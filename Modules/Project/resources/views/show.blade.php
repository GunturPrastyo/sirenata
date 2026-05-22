<x-dashboard::layouts.dashboard title="Detail Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Proyek', 'url' => route($routePrefix . 'index')], ['label' => 'Detail Proyek']]" />

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 max-w-2xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8 border-b border-slate-100 pb-5 sm:pb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Detail Proyek</h1>
                </div>
                <div>
                    <x-badge :color="$project->status === 'Selesai' ? 'success' : 'indigo'" :text="$project->status ?? 'On Progress'" class="uppercase tracking-wider" />
                </div>
            </div>

            <div class="space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Proyek</label>
                    <p class="text-lg font-bold text-slate-800">{{ $project->name }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 border-b border-slate-100 pb-4">
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Mulai</label>
                        <p class="text-sm sm:text-base font-semibold text-slate-700">
                             {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Selesai</label>
                        <p class="text-sm sm:text-base font-semibold text-slate-700">
                             {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Durasi</label>
                        <p class="text-sm sm:text-base font-semibold text-slate-700">{{ $project->duration }} Bulan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-4">
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Tipe Proyek</label>
                        <p class="mt-1">
                            <x-badge color="slate" :text="$project->type" />
                        </p>
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Progress</label>
                        <div class="flex items-center mt-1.5">
                            <span class="text-indigo-600 font-bold text-sm mr-2.5">{{ $project->progress ?? 0 }}%</span>
                            <div class="w-full bg-slate-100 border border-slate-200/60 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-indigo-600 h-2.5 rounded-full shadow-inner shadow-indigo-500/20"
                                    style="width: {{ $project->progress ?? 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Tim Proyek
                    </h3>

                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4 flex items-center justify-between">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Ketua Tim</label>
                            <span class="font-bold text-slate-800 text-sm sm:text-base">{{ $project->leader->name ?? '-' }}</span>
                        </div>
                        <div class="h-10 w-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg shadow-sm">
                            {{ substr($project->leader->name ?? '?', 0, 1) }}
                        </div>
                    </div>

                    <div class="mt-4">
                        @php
                            $teamMembersArr = is_array($project->team_members) ? $project->team_members : json_decode($project->team_members, true) ?? [];
                        @endphp
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                            Anggota Tim ({{ count($teamMembersArr) }})
                        </label>
                        @if(count($teamMembersArr) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach(App\Models\User::whereIn('id', $teamMembersArr)->get() as $member)
                                    <div class="flex items-center p-3 bg-white border border-slate-100 rounded-xl hover:border-indigo-100 hover:shadow-sm hover:shadow-indigo-500/5 transition-all">
                                        <div class="h-8 w-8 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 font-bold mr-3 text-sm">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-slate-700 font-semibold">{{ $member->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 text-center">
                                <p class="text-xs sm:text-sm text-slate-400 italic">Tidak ada anggota tim tambahan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 mt-6 border-t border-slate-100">
                    <x-button :href="route($routePrefix . 'index')" variant="secondary" class="flex-1">
                        Kembali
                    </x-button>
                    @can('project-edit')
                        <x-button :href="route($routePrefix . 'edit', $project->id)" variant="primary" class="flex-1">
                            Edit Proyek
                        </x-button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>