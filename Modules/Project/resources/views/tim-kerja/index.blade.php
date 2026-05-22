<x-dashboard::layouts.dashboard title="Tim Kerja | SIRENATA">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Tim Kerja']]" />

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Proyek Saya</h2>
            </div>

            <x-table.table :plain="true">
                <thead class="bg-gray-50">
                    <tr>
                        <x-table.th>No</x-table.th>
                        <x-table.th>Nama Proyek</x-table.th>
                        <x-table.th>Ketua Tim</x-table.th>
                        <x-table.th>Peran Anda</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th>Periode</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($projects as $index => $project)
                        @php
                            $isLeader = $project->team_leader === auth()->id();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <x-table.td class="whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</x-table.td>
                            <x-table.td class="whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $project->name }}</div>
                                <div class="text-xs text-gray-400">{{ $project->type }}</div>
                            </x-table.td>
                            <x-table.td class="whitespace-nowrap text-sm text-gray-700">
                                {{ $project->leader->name ?? '-' }}
                            </x-table.td>
                            <x-table.td class="whitespace-nowrap">
                                @if($isLeader)
                                    <x-badge color="indigo">Ketua Tim</x-badge>
                                @else
                                    <x-badge color="emerald">Anggota</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td class="whitespace-nowrap">
                                @if($project->status === 'On Progress')
                                    <x-badge color="blue">On Progress</x-badge>
                                @else
                                    <x-badge color="slate">{{ $project->status }}</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td class="whitespace-nowrap text-sm text-gray-500">
                                {{ $project->start_date?->format('d M Y') }} — {{ $project->end_date?->format('d M Y') }}
                            </x-table.td>
                            <x-table.td align="center" class="whitespace-nowrap">
                                <a href="{{ route('user.kalkulator.project', $project->id) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs sm:text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Buka Lembar Kerja
                                </a>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="7" class="py-12 text-center">
                                <div class="text-4xl mb-3">📋</div>
                                <p class="text-sm text-gray-500">Anda belum tergabung dalam proyek manapun.</p>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
