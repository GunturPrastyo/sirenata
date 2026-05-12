<x-dashboard::layouts.dashboard title="Detail Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    <div class="p-2 sm:p-6">

        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route($routePrefix . 'index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Proyek</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Proyek</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-8 max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Proyek</h1>
                    <p class="text-gray-500 text-sm">Informasi lengkap mengenai proyek ini</p>
                </div>
                <span
                    class="px-3 py-1 text-sm font-medium rounded-full 
                    {{ $project->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $project->status ?? 'On Progress' }}
                </span>
            </div>

            <div class="space-y-6">

                <div class="border-b border-gray-100 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Proyek</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $project->name }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 border-b border-gray-100 pb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Mulai</label>
                        <p class="text-base text-gray-900">
                            {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Selesai</label>
                        <p class="text-base text-gray-900">
                            {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Durasi</label>
                        <p class="text-base text-gray-900">{{ $project->duration }} Bulan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-gray-100 pb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tipe Proyek</label>
                        <p class="text-base text-gray-900"><span
                                class="px-2 py-1 bg-gray-100 rounded text-sm font-medium">{{ $project->type }}</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Progress</label>
                        <div class="flex items-center">
                            <span class="text-indigo-600 font-bold mr-2">{{ $project->progress ?? 0 }}%</span>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full"
                                    style="width: {{ $project->progress ?? 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Tim Proyek</h3>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-100">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 mb-2 font-semibold">Ketua
                            Tim</label>
                        <div class="flex items-center">
                            <div
                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3 shadow-sm">
                                {{ substr($project->leader->name ?? '?', 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $project->leader->name ?? '-' }}</span>
                        </div>
                    </div>

                    <div>
                        @php
                            $teamMembersArr = is_array($project->team_members) ? $project->team_members : json_decode($project->team_members, true) ?? [];
                        @endphp
                        <label class="block text-xs uppercase tracking-wider text-gray-500 mb-3 font-semibold">Anggota
                            Tim
                            ({{ count($teamMembersArr) }})</label>
                        @if(count($teamMembersArr) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach(App\Models\User::whereIn('id', $teamMembersArr)->get() as $member)
                                    <div
                                        class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div
                                            class="h-8 w-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600 font-bold mr-3">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-800 font-medium">{{ $member->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center">
                                <p class="text-sm text-gray-500 italic">Tidak ada anggota tim tambahan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex gap-4 pt-6 mt-6 border-t border-gray-200">
                    <a href="{{ route($routePrefix . 'index') }}"
                        class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors text-center shadow-sm">
                        Kembali
                    </a>
                    @can('project-edit')
                        <a href="{{ route($routePrefix . 'edit', $project->id) }}"
                            class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors text-center shadow-sm">
                            Edit Proyek
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>