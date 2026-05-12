<?php

namespace Modules\RTK\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\RTK\Models\RtkSurveyPeriod;

class RtkSurveyPeriodService
{
    public function getPaginatedPeriods(int $limit = 10): LengthAwarePaginator
    {
        RtkSurveyPeriod::where('status', 'aktif')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<', now()->toDateString())
            ->update(['status' => 'tutup']);

        return RtkSurveyPeriod::orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->paginate($limit)
            ->withQueryString();
    }

    public function createPeriod(array $data): RtkSurveyPeriod
    {
        $exists = RtkSurveyPeriod::where('tahun', $data['tahun'])
            ->where('status', '!=', 'tutup')
            ->exists();

        if ($exists) {
            throw new \LogicException(
                "Sudah ada periode aktif/draft untuk tahun {$data['tahun']}."
            );
        }

        $data['status'] = 'draft';

        return RtkSurveyPeriod::create($data);
    }

    public function updatePeriod(RtkSurveyPeriod $period, array $data): RtkSurveyPeriod
    {
        $period->update($data);
        return $period;
    }

    public function activatePeriod(RtkSurveyPeriod $period): RtkSurveyPeriod
    {
        if ($period->tanggal_selesai && $period->tanggal_selesai->lt(now()->startOfDay())) {
            throw new \LogicException(
                'Tidak dapat mengaktifkan periode: tanggal selesai sudah lewat. Silakan edit tanggal selesai terlebih dahulu.'
            );
        }

        RtkSurveyPeriod::where('status', 'aktif')
            ->where('id', '!=', $period->id)
            ->update(['status' => 'tutup']);

        $period->update(['status' => 'aktif']);

        return $period;
    }

    public function closePeriod(RtkSurveyPeriod $period): RtkSurveyPeriod
    {
        $period->update(['status' => 'tutup']);
        return $period;
    }

    public function deletePeriod(RtkSurveyPeriod $period): void
    {
        $period->delete();
    }
}
