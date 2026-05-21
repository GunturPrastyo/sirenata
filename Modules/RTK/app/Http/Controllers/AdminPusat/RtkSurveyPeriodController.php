<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Models\RtkSurveyPeriod;
use Modules\RTK\Models\RtkPemanfaatanSubmission;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Str;

class RtkSurveyPeriodController extends Controller
{
    public function index()
    {
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

        $allPeriods = RtkSurveyPeriod::orderByDesc('tahun')->get();

        return view('rtk::adminPusat.survey-periods.index', compact('periods', 'allPeriods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

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

    public function activate(RtkSurveyPeriod $survey_period)
    {
        if ($survey_period->tanggal_selesai && $survey_period->tanggal_selesai->lt(now()->startOfDay())) {
            ToastMagic::error('Tidak dapat mengaktifkan periode: tanggal selesai sudah lewat. Silakan edit tanggal selesai terlebih dahulu.');
            return redirect()->route('admin-pusat.survey-periods.index');
        }

        RtkSurveyPeriod::where('status', 'aktif')
            ->where('id', '!=', $survey_period->id)
            ->update(['status' => 'tutup']);

        $survey_period->update(['status' => 'aktif']);
        ToastMagic::success("Periode \"{$survey_period->nama}\" berhasil diaktifkan.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }

    public function close(RtkSurveyPeriod $survey_period)
    {
        $survey_period->update(['status' => 'tutup']);
        ToastMagic::success("Periode \"{$survey_period->nama}\" berhasil ditutup.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }

    public function destroy(RtkSurveyPeriod $survey_period)
    {
        $nama = $survey_period->nama;
        $survey_period->delete();
        ToastMagic::success("Periode \"{$nama}\" berhasil dihapus.");
        return redirect()->route('admin-pusat.survey-periods.index');
    }

    /**
     * Copy all verified submissions from this period to a target period.
     */
    public function copySubmissions(Request $request, RtkSurveyPeriod $survey_period)
    {
        $request->validate([
            'target_period_id' => 'required|uuid|exists:rtk_survey_periods,id',
        ]);

        $targetPeriodId = $request->input('target_period_id');

        if ($survey_period->id === $targetPeriodId) {
            ToastMagic::error('Periode sumber dan tujuan tidak boleh sama.');
            return redirect()->route('admin-pusat.survey-periods.index');
        }

        $verifiedSubmissions = RtkPemanfaatanSubmission::where('period_id', $survey_period->id)
            ->where('status_verifikasi', 'verified')
            ->get();

        if ($verifiedSubmissions->isEmpty()) {
            ToastMagic::error('Tidak ada data terverifikasi pada periode ini untuk disalin.');
            return redirect()->route('admin-pusat.survey-periods.index');
        }

        $existingUserIds = RtkPemanfaatanSubmission::where('period_id', $targetPeriodId)
            ->pluck('user_id')
            ->toArray();

        $copied = 0;
        $skipped = 0;

        foreach ($verifiedSubmissions as $submission) {
            if (in_array($submission->user_id, $existingUserIds)) {
                $skipped++;
                continue;
            }

            $newSubmission = $submission->replicate();
            $newSubmission->id = Str::uuid()->toString();
            $newSubmission->period_id = $targetPeriodId;
            $newSubmission->created_by = auth()->id();
            $newSubmission->status_verifikasi = 'verified';
            $newSubmission->catatan_verifikasi = 'Disalin dari periode "' . $survey_period->nama . '" oleh Admin Pusat.';
            $newSubmission->field_verifications = null;
            $newSubmission->created_at = now();
            $newSubmission->updated_at = now();
            $newSubmission->save();

            $copied++;
        }

        $targetPeriod = RtkSurveyPeriod::find($targetPeriodId);
        $message = "Berhasil menyalin {$copied} data ke periode \"{$targetPeriod->nama}\".";
        if ($skipped > 0) {
            $message .= " ({$skipped} data dilewati karena sudah ada.)";
        }

        ToastMagic::success($message);
        return redirect()->route('admin-pusat.survey-periods.index');
    }
}
