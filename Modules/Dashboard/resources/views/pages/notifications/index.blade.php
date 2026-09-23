<x-dashboard::layouts.dashboard title="Notifikasi">
    <div class="mx-auto w-full max-w-4xl space-y-6 bg-slate-50/50 p-4 min-h-screen sm:p-6 lg:p-8"
        x-data="notificationPage()" x-init="boot()">

        <!-- Header (kalem, tanpa kartu mencolok) -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-[#13416B]/[0.07] text-[#13416B]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">Notifikasi</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Pemberitahuan persetujuan proyek serta status verifikasi &amp; persetujuan RTKD
                        wilayah Anda.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Penyegar ringan (event-driven: data sudah tersimpan saat kejadian terjadi) -->
                <span
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-500">
                    <i class="fas fa-rotate text-[10px]"></i> Diperbarui otomatis
                </span>

                <span data-unread-count="{{ (int) $unreadCount }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-rose-50 px-3 py-1.5 text-[11px] font-bold text-rose-500"
                    @if (($unreadCount ?? 0) === 0) style="display: none" @endif>
                    <span>{{ (int) $unreadCount }} Belum Dibaca</span>
                </span>

                <button type="button" data-notif-read-all :disabled="unread <= 0 || busy"
                    class="cursor-pointer rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 transition-colors hover:border-[#13416B]/40 hover:text-[#13416B] disabled:cursor-not-allowed disabled:opacity-40">
                    Tandai semua dibaca
                </button>
            </div>
        </div>

        <!-- Chip pembaruan: ada baris baru yang belum tampil -->
        <div x-show="pending > 0" x-cloak x-transition
            class="sticky top-24 z-30 flex items-center justify-between gap-3 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3">
            <p class="text-sm font-bold text-indigo-800">
                <span x-text="pending"></span> notifikasi baru masuk.
            </p>
            <button type="button" @click="reload"
                class="cursor-pointer rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-700">
                Segarkan
            </button>
        </div>

        <!-- Tab filter -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('notifications.index') }}"
                class="rounded-lg px-4 py-2 text-sm font-bold transition-colors {{ ($filter ?? '') !== 'unread' ? 'bg-[#13416B] text-white' : 'border border-slate-200 bg-white text-slate-600 hover:border-[#13416B]/40' }}">
                Semua
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-bold transition-colors {{ ($filter ?? '') === 'unread' ? 'bg-[#13416B] text-white' : 'border border-slate-200 bg-white text-slate-600 hover:border-[#13416B]/40' }}">
                Belum dibaca
                @if (($unreadCount ?? 0) > 0)
                    <span
                        class="grid h-5 min-w-[20px] place-items-center rounded-full bg-rose-500 px-1.5 text-[10px] font-extrabold text-white">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Daftar notifikasi -->
        <div id="notif-list" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @forelse ($notifications as $notification)
                @include('dashboard::partials.notifications.item', ['notification' => $notification])
            @empty
                <div class="px-6 py-16 text-center">
                    <i class="far fa-bell-slash text-3xl text-slate-200"></i>
                    <p class="mt-4 text-sm font-bold text-slate-700">
                        {{ ($filter ?? '') === 'unread' ? 'Tidak ada notifikasi yang belum dibaca' : 'Belum ada notifikasi' }}
                    </p>
                    <p class="mx-auto mt-1 max-w-sm text-xs leading-relaxed text-slate-400">
                        Pemberitahuan persetujuan proyek dan status RTKD akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($notifications->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            const NOTIF_PAGE_ROUTES = {
                read: @js(route('notifications.read', ['notification' => '__ID__'])),
                readAll: @js(route('notifications.read-all')),
                destroy: @js(route('notifications.destroy', ['notification' => '__ID__'])),
                latest: @js(route('notifications.latest')),
            };
            const NOTIF_PAGE_POLL_MS = {{ max(10, (int) config('dashboard.notifications.poll_interval', 30)) }} * 1000;

            function notificationPage() {
                return {
                    busy: false,
                    pending: 0,
                    unread: {{ (int) $unreadCount }},
                    knownIds: null,

                    boot() {
                        this.knownIds = new Set(
                            [...document.querySelectorAll('[data-notif-id]')]
                                .map((row) => row.dataset.notifId)
                        );

                        // Notifikasi sudah tersimpan di database saat kejadian
                        // terjadi; polling ini hanya menarik baris terbaru.
                        setInterval(() => this.check(), NOTIF_PAGE_POLL_MS);

                        document.addEventListener('visibilitychange', () => {
                            if (document.visibilityState === 'visible') this.check();
                        });
                    },

                    async post(url, method = 'POST') {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
                        const response = await fetch(url, {
                            method,
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({})
                        });
                        return response.json();
                    },

                    /** Deteksi baris baru → tampilkan chip "Segarkan". */
                    async check() {
                        try {
                            const response = await fetch(NOTIF_PAGE_ROUTES.latest, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                credentials: 'same-origin'
                            });
                            if (!response.ok) return;

                            const payload = await response.json();
                            if (!payload || !Array.isArray(payload.items)) return;

                            this.pending = payload.items.filter((item) => !this.knownIds.has(String(item.id))).length;
                        } catch (error) {
                            /* abaikan — biarkan isi halaman yang tampil */
                        }
                    },

                    async markAll() {
                        if (this.busy || this.unread <= 0) return;

                        this.busy = true;
                        try {
                            const result = await this.post(NOTIF_PAGE_ROUTES.readAll);
                            this.unread = result.unread_count || 0;
                            this.pending = 0;
                            this.reload();
                        } finally {
                            this.busy = false;
                        }
                    },

                    reload() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('live');
                        window.location.href = url.toString();
                    },
                };
            }

            /* Aksi per-item: buang (X), tandai dibaca, buka tautan. */
            document.addEventListener('click', async (event) => {
                // 1) Tombol buang (X)
                const dismissButton = event.target.closest('[data-notif-dismiss]');
                if (dismissButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    dismissButton.disabled = true;

                    const row = dismissButton.closest('[data-notif-id]');
                    const wasUnread = row && row.classList.contains('bg-slate-50/60');

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
                        const response = await fetch(
                            NOTIF_PAGE_ROUTES.destroy.replace('__ID__', dismissButton.dataset.notifDismiss),
                            {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify({})
                            }
                        );
                        const result = await response.json();

                        if (wasUnread && typeof result.unread_count === 'number') {
                            const chip = document.querySelector('[data-unread-count]');
                            if (chip) {
                                chip.style.display = result.unread_count === 0 ? 'none' : '';
                                chip.querySelector('span').textContent = result.unread_count + ' Belum Dibaca';
                            }
                        }
                    } catch (error) {
                        dismissButton.disabled = false;
                        return;
                    }

                    if (row) {
                        row.style.transition = 'opacity .18s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            if (!document.querySelector('[data-notif-id]')) {
                                location.reload();
                            }
                        }, 180);
                    }

                    return;
                }

                // 2) Tandai dibaca / buka tautan
                const readButton = event.target.closest('[data-notif-read]');
                const openButton = event.target.closest('[data-notif-open]');
                const row = event.target.closest('[data-notif-id]');

                if (!readButton && !openButton) return;

                event.preventDefault();

                const id = readButton?.dataset.notifRead || openButton?.dataset.notifOpen;
                if (!id) return;

                try {
                    await fetch(NOTIF_PAGE_ROUTES.read.replace('__ID__', id), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({})
                    });
                } catch (error) {
                    /* tetap lanjut */
                }

                if (openButton?.getAttribute('href')) {
                    window.location.href = openButton.getAttribute('href');
                } else {
                    window.location.reload();
                }
            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
