<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Models\RtkPemanfaatanSubmission;
use Modules\RTK\Models\RtkSurveyPeriod;
use Illuminate\Support\Facades\Storage;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class HasilPemanfaatanRtkdController extends Controller
{
    /**
     * Menampilkan daftar hasil kuesioner dengan filter.
     */
    public function index(Request $request)
    {
        // Ambil semua periode survei untuk dropdown filter
        $periods = RtkSurveyPeriod::orderBy('tahun', 'desc')->get();
        
        // Tentukan periode yang dipilih (default ke periode aktif, atau yang paling baru jika tidak ada)
        $selectedPeriodId = $request->input('period_id');
        if (!$selectedPeriodId) {
            $activePeriod = RtkSurveyPeriod::aktif()->first();
            $selectedPeriodId = $activePeriod ? $activePeriod->id : ($periods->first() ? $periods->first()->id : null);
        }

        $query = RtkPemanfaatanSubmission::with(['user', 'period']);

        // Filter berdasarkan Periode
        if ($selectedPeriodId) {
            $query->where('period_id', $selectedPeriodId);
        }

        // Filter berdasarkan Kepemilikan RTKD
        if ($request->filled('q1_punya_rtkd')) {
            $query->where('q1_punya_rtkd', $request->input('q1_punya_rtkd'));
        }

        // Filter berdasarkan Pemanfaatan Acuan
        if ($request->filled('q2_jadi_acuan')) {
            $query->where('q2_jadi_acuan', $request->input('q2_jadi_acuan'));
        }

        // Filter pencarian berdasarkan nama provinsi (user->name)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate(20)->withQueryString();

        return view('rtk::adminPusat.hasil-pemanfaatan.index', compact('submissions', 'periods', 'selectedPeriodId'));
    }

    /**
     * Menampilkan rincian kuesioner untuk diverifikasi.
     */
    public function show($id)
    {
        $submission = RtkPemanfaatanSubmission::with(['user', 'period', 'rtkDocument'])->findOrFail($id);
        
        return view('rtk::adminPusat.hasil-pemanfaatan.show', compact('submission'));
    }

    /**
     * Memproses verifikasi kuesioner (Per-field).
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'verifications' => 'required|array',
            'verifications.*.status' => 'required|in:verified,rejected',
            'verifications.*.catatan' => 'nullable|string',
        ]);

        $submission = RtkPemanfaatanSubmission::findOrFail($id);
        
        $verifications = $request->input('verifications');
        
        // Determine global status
        $hasRejection = false;
        foreach ($verifications as $field => $data) {
            if ($data['status'] === 'rejected') {
                $hasRejection = true;
                break;
            }
        }

        $submission->field_verifications = $verifications;
        $submission->status_verifikasi = $hasRejection ? 'rejected' : 'verified';
        
        // Clear global notes, we rely on field notes now
        $submission->catatan_verifikasi = null; 
        
        $submission->save();

        $statusMsg = $submission->status_verifikasi === 'verified' 
            ? 'Kuesioner berhasil disetujui sepenuhnya.' 
            : 'Kuesioner dikembalikan ke provinsi untuk direvisi pada bagian tertentu.';

        ToastMagic::success($statusMsg);

        return redirect()->route('admin-pusat.hasil-pemanfaatan-rtkd.show', $submission->id);
    }
}
