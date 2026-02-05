<?php

namespace Modules\RTK\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class RTKNService
{
    public function getFilteredQueryBuilderRTKN(?string $search = null, string $sortBy = 'desc', ?string $status = null)
    {
        return DB::table('rencana_tenaga_kerjas')
            ->where('type', TypeRtk::NASIONAL->value)
            ->when($search, fn($query) => $query->where('nama', 'like', "%{$search}%"))
            ->when($status, fn($query) => $query->where('status', $status))
            ->orderBy('created_at', $sortBy);
    }

    public function paginateFilteredRTKN(?string $search = null, string $sortBy = 'desc', int $limit = 15, ?string $status = null)
    {
        return $this->getFilteredQueryBuilderRTKN($search, $sortBy, $status)
            ->paginate($limit)
            ->withQueryString();
    }

    public function createRTKN(array $data)
    {
        $user = Auth::user();
        return DB::transaction(function () use ($data, $user) {
            $documentPath = null;
            if (isset($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkn/documents',
                    'public'
                );
            }
            $rtkn = RencanaTenagaKerja::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => RTKStatus::PENDING->value,
                'type' => TypeRtk::NASIONAL->value,
                'document_path' => $documentPath,
            ]);

            ToastMagic::success("RTKN berhasil ditambahkan!");

            return $rtkn;
        });
    }

    public function updateRTKN(array $data, RencanaTenagaKerja $rencanaTenagaKerjaNasional)
    {
        $user = Auth::user();
        return DB::transaction(function () use ($data, $user, $rencanaTenagaKerjaNasional) {
            $documentPath = $rencanaTenagaKerjaNasional->document_path;
            if (isset($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkn/documents',
                    'public'
                );
            }
            $rencanaTenagaKerjaNasional->update([
                'user_id' => $user->id,
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'] ?? RTKStatus::PENDING->value,
                'type' => TypeRtk::NASIONAL->value,
                'document_path' => $documentPath,
            ]);

            ToastMagic::success("RTKN berhasil diupdate!");

            return $rencanaTenagaKerjaNasional;
        });
    }

    public function deleteRTKN(RencanaTenagaKerja $rencanaTenagaKerjaNasional)
    {
        if ($rencanaTenagaKerjaNasional->document_path) {
            Storage::disk('public')->delete($rencanaTenagaKerjaNasional->document_path);
        }
        $rencanaTenagaKerjaNasional->delete();

        ToastMagic::success("RTKN berhasil dihapus!");
        return $rencanaTenagaKerjaNasional;
    }
}
