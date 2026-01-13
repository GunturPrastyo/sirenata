<x-layouts.app title="Activity Log">
    <div class="w-full max-w-6xl mx-auto">

        <h1 class="text-xl font-semibold mb-4">Activity Log</h1>

        <x-flash-message class="my-3" />

        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
            <table class="w-full text-sm text-left text-body">
                <thead class="bg-neutral-secondary-soft border-b border-default">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Subject</th>
                        <th class="px-6 py-3">Change</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($activities as $key => $activity)
                    @php
                        $old = $activity->properties['old'] ?? [];
                        $new = $activity->properties['attributes'] ?? [];
                    @endphp

                    <tr class="border-b border-default">
                        {{-- NO --}}
                        <td class="px-6 py-4 font-medium">
                            {{ $key + $activities->firstItem() }}
                        </td>

                        {{-- WAKTU --}}
                        <td class="px-6 py-4">
                            {{ $activity->created_at
                                ->timezone('Asia/Jakarta')
                                ->translatedFormat('d M Y H:i') }}
                        </td>

                        {{-- DESKRIPSI --}}
                        <td class="px-6 py-4">
                            {{ $activity->description }}
                        </td>

                        {{-- CAUSER --}}
                        <td class="px-6 py-4">
                            {{ optional($activity->causer)->name ?? 'System' }}
                        </td>

                        {{-- SUBJECT --}}
                        <td class="px-6 py-4">
                            @if ($activity->subject)
                                <div class="space-y-0.5">
                                    <span class="font-medium text-heading">
                                        {{ $activity->subject->name ?? '—' }}
                                    </span> 
                                    <div>
                                        <span class="inline-block text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                            {{ class_basename($activity->subject_type) }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-sm">System</span>
                            @endif
                        </td>

                        {{-- CHANGE --}}
                        <td class="px-6 py-4">
                            <div class="space-y-1">

                                {{-- LABEL --}}
                                @if ($activity->log_name === 'user-role')
                                    <span class="inline-block text-xs px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 font-medium">
                                        Role
                                    </span>
                                @elseif ($activity->log_name === 'user-permission')
                                    <span class="inline-block text-xs px-2 py-0.5 rounded bg-green-100 text-green-700 font-medium">
                                        Permission
                                    </span>
                                @else
                                    <span class="inline-block text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium">
                                        Update
                                    </span>
                                @endif

                                {{-- VALUE --}}
                                @if (!empty($old) || !empty($new))
                                    <div class="flex items-center gap-2 flex-wrap text-sm">
                                        @foreach ($old as $value)
                                            <span class="px-2 py-1 rounded bg-gray-200 text-gray-700">
                                                {{ $value }}
                                            </span>
                                        @endforeach

                                        @if (!empty($old) && !empty($new))
                                            <span class="text-gray-400 font-semibold">→</span>
                                        @endif

                                        @foreach ($new as $value)
                                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 font-semibold">
                                                {{ $value }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-sm">No changes</span>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-400">
                            No activity found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    </div>
</x-layouts.app>
