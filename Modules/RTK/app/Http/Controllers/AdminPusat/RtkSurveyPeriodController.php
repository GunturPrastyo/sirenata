<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Models\RtkSurveyPeriod;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class RtkSurveyPeriodController extends Controller
{
    /**
     * Display a listing of survey periods.
     */
    public function index()
    {
        // Auto-close periode yang tanggal selesainya sudah lewat
        RtkSurveyPeriod::where('status', 'aktif')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<', now()->toDateString())
            ->update(['status' => 'tutup']);

        $periods = RtkSurveyPeriod::withCount(['submissions' => function ($query) {
            $query->where('status_verifikasi', 'verified');
        }])
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->paginate(10);


        return view('rtk::adminPusat.survey-periods.index', compact('periods'));
    }

    /**
     * Store a newly created survey period.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        // Cek apakah sudah ada periode aktif/draft untuk tahun tersebut
        $exists = RtkSurveyPeriod::where('tahun', $validated['tahun'])
            ->where('status', '!=', 'tutup')
            ->exists();

        if ($exists) {
            ToastMagic::error("Sudah ada periode aktif/draft untuk tahun {$validated['tahun']}.");
            return redirect()->route('admin-pusat.survey-periods.index');
        }

        $validated['status'] = 'draft';

        RtkSurveyPeriod::create($validated);
        ToastMagic::success("Periode \"{$validated['nama']}\" berhasil dibuat.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }

    /**
     * Update the specified survey period.
     */
    public function update(Request $request, RtkSurveyPeriod $survey_period)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $survey_period->update($validated);
        ToastMagic::success("Periode \"{$validated['nama']}\" berhasil diperbarui.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }

    /**
     * Activate a survey period (only one can be active at a time).
     */
    public function activate(RtkSurveyPeriod $survey_period)
    {
        // Cek apakah tanggal selesai sudah lewat
        if ($survey_period->tanggal_selesai && $survey_period->tanggal_selesai->lt(now()->startOfDay())) {
            ToastMagic::error('Tidak dapat mengaktifkan periode: tanggal selesai sudah lewat. Silakan edit tanggal selesai terlebih dahulu.');
            return redirect()->route('admin-pusat.survey-periods.index');
        }

        // Tutup semua periode aktif lainnya
        RtkSurveyPeriod::where('status', 'aktif')
            ->where('id', '!=', $survey_period->id)
            ->update(['status' => 'tutup']);

        // Aktifkan periode ini
        $survey_period->update(['status' => 'aktif']);
        ToastMagic::success("Periode \"{$survey_period->nama}\" berhasil diaktifkan.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }

    /**
     * Close a survey period.
     */
    public function close(RtkSurveyPeriod $survey_period)
    {
        $survey_period->update(['status' => 'tutup']);
        ToastMagic::success("Periode \"{$survey_period->nama}\" berhasil ditutup.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }

    /**
     * Remove the specified survey period.
     */
    public function destroy(RtkSurveyPeriod $survey_period)
    {
        $nama = $survey_period->nama;
        $survey_period->delete();
        ToastMagic::success("Periode \"{$nama}\" berhasil dihapus.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }
}
