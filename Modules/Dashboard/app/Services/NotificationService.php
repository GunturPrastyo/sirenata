<?php

namespace Modules\Dashboard\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\Dashboard\Models\Notification;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Throwable;

/**
 * Pembuat notifikasi untuk Admin Provinsi & Admin Kab/Kota.
 *
 * Fokus konteks:
 *  1. Proyek daerah (Kab/Kota & Provinsi) disetujui Admin Pusat
 *  2. RTKD Kab/Kota & Provinsi sudah diverifikasi
 *  3. RTKD Kab/Kota & Provinsi sudah disetujui (dokumen berlaku)
 *  4. RTKD Kab/Kota & Provinsi ditolak (bonus, tetap satu alur)
 *
 * Seluruh proses pembuatan notifikasi dibungkus try/catch agar
 * kegagalan notifikasi tidak pernah merusak alur persetujuan.
 */
class NotificationService
{
    public const PROJECT_APPROVED = 'project.approved';
    public const PROJECT_COMPLETED = 'project.completed';
    public const RTKD_VERIFIED = 'rtkd.verified';
    public const RTKD_APPROVED = 'rtkd.approved';
    public const RTKD_REJECTED = 'rtkd.rejected';

    private const RECIPIENT_ROLES = ['admin-province', 'admin-kab-kota'];

    /* ------------------------------------------------------------------ */
    /* PROYEK                                                              */
    /* ------------------------------------------------------------------ */

    /**
     * Beri tahu pemilik & sesama admin wilayah bahwa proyek daerah disetujui.
     *
     * @param  int|string  $projectId
     * @param  string  $projectType  'Provinsi' | 'Kab/Kota' | 'Nasional'
     */
    public function projectApproved(
        int|string $projectId,
        string $projectName,
        string $projectType,
        ?string $creatorId = null,
        string $previousStatus = 'Draft',
        string $newStatus = 'On Progress',
    ): void {
        try {
            $isCompleted = $newStatus === 'Completed';

            $title = $isCompleted ? 'Proyek Selesai' : 'Proyek Disetujui';
            $message = $isCompleted
                ? "Proyek \"{$projectName}\" telah ditandai selesai oleh Admin Pusat."
                : "Proyek \"{$projectName}\" telah disetujui Admin Pusat dan siap dilaksanakan. Silakan lengkapi ketua serta anggota tim.";

            foreach ($this->projectRecipients($projectType, $creatorId) as $user) {
                $this->push(
                    user: $user,
                    type: $isCompleted ? self::PROJECT_COMPLETED : self::PROJECT_APPROVED,
                    title: $title,
                    message: $message,
                    link: $this->projectLink($user, $projectId),
                    meta: [
                        'project_id'   => (string) $projectId,
                        'project_type' => $projectType,
                        'group'        => $isCompleted ? 'Proyek Selesai' : 'Proyek Disetujui',
                    ],
                );
            }
        } catch (Throwable $e) {
            Log::warning('Notifikasi persetujuan proyek gagal dibuat: ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /* RTKD                                                                */
    /* ------------------------------------------------------------------ */

    /**
     * Notifikasi status RTKD (kab/kota & provinsi).
     *
     * @param  string  $event  verified | approved | rejected
     * @param  string  $rtkType  'Kab/Kota' | 'Provinsi'
     */
    public function rtkdStatus(
        string $event,
        string $rtkName,
        string $rtkType,
        ?string $provinceCode = null,
        ?string $regencyCode = null,
        ?string $creatorId = null,
        ?string $reason = null,
    ): void {
        $type = match ($event) {
            'verified' => self::RTKD_VERIFIED,
            'approved' => self::RTKD_APPROVED,
            'rejected' => self::RTKD_REJECTED,
            default    => 'system.general',
        };

        try {
            $scopeName = $this->scopeName($provinceCode, $regencyCode);
            $where = $scopeName ? " ({$scopeName})" : '';

            [$title, $message] = match ($event) {
                'verified' => [
                    'RTKD Diverifikasi',
                    "RTKD \"{$rtkName}\"{$where} telah diverifikasi dan menunggu persetujuan dokumen.",
                ],
                'approved' => [
                    'RTKD Disetujui',
                    "RTKD \"{$rtkName}\"{$where} telah disetujui dan dokumennya kini berstatus berlaku.",
                ],
                'rejected' => [
                    'RTKD Ditolak',
                    "RTKD \"{$rtkName}\"{$where} ditolak." .
                        ($reason ? " Alasan: {$reason}" : ''),
                ],
                default => ['Pembaruan RTKD', "RTKD \"{$rtkName}\"{$where} mengalami pembaruan status."],
            };

            $group = match ($event) {
                'verified' => 'Verifikasi RTKD',
                'approved' => 'Persetujuan RTKD',
                'rejected' => 'RTKD Ditolak',
                default    => Notification::typeMeta($type)['label'],
            };

            foreach ($this->rtkdRecipients($provinceCode, $regencyCode, $creatorId) as $user) {
                $this->push(
                    user: $user,
                    type: $type,
                    title: $title,
                    message: $message,
                    link: $this->rtkdLink($user),
                    meta: [
                        'rtk_type'     => $rtkType,
                        'province'     => $provinceCode,
                        'regency'      => $regencyCode,
                        'scope_name'   => $scopeName,
                        'group'        => $group,
                    ],
                );
            }
        } catch (Throwable $e) {
            Log::warning('Notifikasi RTKD gagal dibuat: ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /* Penerima                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Penerima notifikasi proyek: pembuat proyek + seluruh admin
     * se-wilayah yang sama (scope creator).
     *
     * @return \Illuminate\Support\Collection<int, User>
     */
    private function projectRecipients(string $projectType, ?string $creatorId)
    {
        $role = match ($projectType) {
            'Provinsi', 'provinsi', 'PROVINSI' => 'admin-province',
            default => 'admin-kab-kota',
        };

        $ids = collect();

        if ($creatorId) {
            $ids->push($creatorId);

            $creator = User::find($creatorId);
            $scope = $creator?->scopeArea;

            if ($scope) {
                $siblings = User::role($role)
                    ->whereHas('scopeArea', function ($query) use ($role, $scope) {
                        if ($role === 'admin-province') {
                            $query->where('province_code', $scope->province_code);
                        } else {
                            $query->where('regency_code', $scope->regency_code);
                        }
                    })
                    ->pluck('id');

                $ids = $ids->merge($siblings);
            }
        }

        return $this->resolveAdmins($ids);
    }

    /**
     * Penerima notifikasi RTKD:
     *  - RTKD Kab/Kota  → Admin Kab/Kota wilayah itu + Admin Provinsi induk
     *  - RTKD Provinsi  → Admin Provinsi wilayah itu
     *
     * @return \Illuminate\Support\Collection<int, User>
     */
    private function rtkdRecipients(?string $provinceCode, ?string $regencyCode, ?string $creatorId)
    {
        $ids = collect();

        if ($regencyCode) {
            $ids = $ids->merge(
                User::role('admin-kab-kota')
                    ->whereHas('scopeArea', fn ($query) => $query->where('regency_code', $regencyCode))
                    ->pluck('id')
            );
        }

        if ($provinceCode) {
            $ids = $ids->merge(
                User::role('admin-province')
                    ->whereHas('scopeArea', fn ($query) => $query->where('province_code', $provinceCode))
                    ->pluck('id')
            );
        }

        if ($creatorId) {
            $ids->push($creatorId);
        }

        return $this->resolveAdmins($ids);
    }

    /**
     * Ambil user admin (role yang punya akses navbar notifikasi).
     *
     * @param  \Illuminate\Support\Collection<int, mixed>  $ids
     * @return \Illuminate\Support\Collection<int, User>
     */
    private function resolveAdmins($ids)
    {
        $ids = $ids->filter()->unique()->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        return User::whereIn('id', $ids)
            ->get()
            ->filter(fn (User $user) => $user->hasAnyRole(self::RECIPIENT_ROLES))
            ->values();
    }

    /* ------------------------------------------------------------------ */
    /* Tautan                                                              */
    /* ------------------------------------------------------------------ */

    private function projectLink(User $user, int|string $projectId): string
    {
        return $user->hasRole('admin-kab-kota')
            ? route('admin-kab-kota.project.show', $projectId)
            : route('admin-province.project.show', $projectId);
    }

    private function rtkdLink(User $user): string
    {
        return $user->hasRole('admin-kab-kota')
            ? route('admin-kab-kota.rtkd.index')
            : route('admin-province.rtkdp.index');
    }

    private function scopeName(?string $provinceCode, ?string $regencyCode): ?string
    {
        if ($regencyCode) {
            return Regency::where('code', $regencyCode)->value('name');
        }

        if ($provinceCode) {
            return Province::where('code', $provinceCode)->value('name');
        }

        return null;
    }

    /* ------------------------------------------------------------------ */
    /* Penyimpanan                                                         */
    /* ------------------------------------------------------------------ */

    /**
     * @param  array<string, mixed>  $meta
     */
    private function push(User $user, string $type, string $title, string $message, ?string $link, array $meta = []): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'meta'    => $meta ?: null,
            'read_at' => null,
        ]);
    }
}
