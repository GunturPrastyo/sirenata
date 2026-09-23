{{--
    Baris notifikasi untuk halaman "Lihat Semua Notifikasi".
    Flat (tanpa kartu) + tombol "X" untuk membuang notifikasi.

    $notification — Modules\Dashboard\Models\Notification
--}}
@php($notifMeta = \Modules\Dashboard\Models\Notification::typeMeta($notification->type))

<div data-notif-id="{{ $notification->id }}"
    class="group relative flex items-start gap-3 border-b border-slate-100 px-4 py-3.5 transition-colors last:border-b-0 {{ $notification->read_at ? 'bg-white hover:bg-slate-50/70' : 'bg-slate-50/60 hover:bg-slate-100/60' }}">

    <!-- Ikon kecil warna kalem -->
    <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-lg {{ $notification->meta['tile'] ?? $notifMeta['tile'] }}">
        <i class="{{ $notification->meta['icon'] ?? $notifMeta['icon'] }} text-sm"></i>
    </span>

    <!-- Konten -->
    <div class="min-w-0 flex-1">
        <div class="flex flex-wrap items-center gap-2">
            <h3 class="text-[13.5px] font-semibold text-slate-700">{{ $notification->title }}</h3>
            @if (!$notification->read_at)
                <span class="h-1.5 w-1.5 rounded-full bg-[#13416B]"></span>
            @endif
            <span class="text-[11px] font-medium text-slate-400">
                <i class="far fa-clock me-1"></i>{{ $notification->time_ago }}
            </span>
        </div>

        <p class="mt-0.5 text-[12.5px] leading-relaxed text-slate-500">{{ $notification->message }}</p>

        <div class="mt-1.5 flex flex-wrap items-center gap-4">
            @if (!$notification->read_at)
                <button type="button" data-notif-read="{{ $notification->id }}"
                    class="cursor-pointer text-[11px] font-semibold text-[#13416B] hover:underline">Tandai
                    dibaca</button>
            @endif

            @if ($notification->link)
                <a href="{{ $notification->link }}" data-notif-open="{{ $notification->id }}"
                    class="text-[11px] font-semibold text-slate-400 hover:text-[#13416B] hover:underline">Buka
                    detail</a>
            @endif
        </div>
    </div>

    <!-- Tombol buang (X) -->
    <button type="button" data-notif-dismiss="{{ $notification->id }}" aria-label="Hapus notifikasi"
        class="mt-1 grid h-7 w-7 shrink-0 cursor-pointer place-items-center rounded-md text-slate-300 transition-colors hover:bg-slate-100 hover:text-slate-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#13416B]/30">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            viewBox="0 0 24 24" aria-hidden="true">
            <path d="M18 6 6 18M6 6l12 12" />
        </svg>
    </button>
</div>
