<?php

namespace Modules\RTK\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;

class RTKNService
{
    public function getFilteredQueryBuilderRTKN(?string $search = null, string $sortBy = 'desc', ?string $status = null)
    {
        return DB::table('rencana_tenaga_kerjas')
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
            // 1️⃣ Upload file
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

            return $rtkn;
        });
    }
}
