<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Dashboard\Models\Notification;

/**
 * Notifikasi berbasis kejadian (event-driven push).
 *
 * Baris notifikasi sudah dibuat oleh listener ketika kejadian
 * (persetujuan / verifikasi) terjadi; controller ini hanya
 * menyajikan, menandai dibaca, dan membuang notifikasi.
 */
class NotificationController extends Controller
{
    /**
     * Halaman "Lihat Semua Notifikasi".
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->string('filter')->toString();

        $query = Notification::query()
            ->forUser($user->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($filter === 'unread') {
            $query->unread();
        }

        $notifications = $query->paginate($this->perPage())->withQueryString();
        $unreadCount = Notification::forUser($user->id)->unread()->count();

        return view('dashboard::pages.notifications.index', [
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
            'filter'        => $filter,
        ]);
    }

    /**
     * Snapshot notifikasi terbaru — dipakai navbar untuk menyegarkan
     * tampilan setelah ada kejadian baru di database.
     */
    public function latest(Request $request): JsonResponse
    {
        return response()->json($this->payload($request->user()->id));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function read(Request $request, Notification $notification): JsonResponse
    {
        abort_if($notification->user_id !== $request->user()->id, 404);

        $notification->markAsRead();

        return response()->json([
            'success'      => true,
            'unread_count' => $this->unreadCount($request->user()->id),
        ]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function readAll(Request $request): JsonResponse
    {
        Notification::forUser($request->user()->id)
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json([
            'success'      => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Buang satu notifikasi melalui tombol "X".
     */
    public function destroy(Request $request, Notification $notification): JsonResponse
    {
        abort_if($notification->user_id !== $request->user()->id, 404);

        $notification->delete();

        return response()->json([
            'success'      => true,
            'unread_count' => $this->unreadCount($request->user()->id),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /* Internal                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Snapshot notifikasi terbaru untuk dropdown navbar.
     *
     * @return array{items: array<int, array<string, mixed>>, unread_count: int, server_time: string}
     */
    private function payload(string $userId): array
    {
        $items = Notification::forUser($userId)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($this->perPage())
            ->get();

        return [
            'items'        => $items->map(fn (Notification $n) => $n->toPayload())->values()->all(),
            'unread_count' => $this->unreadCount($userId),
            'server_time'  => now()->toIso8601String(),
        ];
    }

    private function unreadCount(string $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    private function perPage(): int
    {
        return max(5, (int) config('dashboard.notifications.per_page', 15));
    }
}
