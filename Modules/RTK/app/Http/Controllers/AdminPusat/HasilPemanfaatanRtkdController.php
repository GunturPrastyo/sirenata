<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Models\RtkPemanfaatanSubmission;
use Modules\RTK\Models\RtkSurveyPeriod;
use Modules\RTK\Models\RencanaTenagaKerja;
use Illuminate\Support\Facades\Storage;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RTK\Exports\HasilPemanfaatanRtkdExport;

class HasilPemanfaatanRtkdController extends Controller
{
    public function export(Request $request)
    {
        $filename = 'Hasil Pemanfaatan RTKD' . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new HasilPemanfaatanRtkdExport(
                periodId: $request->input('period_id'),
                q1PunyaRtkd: $request->input('q1_punya_rtkd'),
                q2JadiAcuan: $request->input('q2_jadi_acuan'),
                search: $request->input('search'),
            ),
            $filename
        );
    }

    public function index(Request $request)
    {
        $periods = RtkSurveyPeriod::orderBy('tahun', 'desc')->get();
        
        $selectedPeriodId = $request->input('period_id');
        if (!$selectedPeriodId) {
            $activePeriod = RtkSurveyPeriod::aktif()->first();
            $selectedPeriodId = $activePeriod ? $activePeriod->id : ($periods->first() ? $periods->first()->id : null);
        }

        $query = RtkPemanfaatanSubmission::with(['user.scopeArea.province', 'period']);

        if ($selectedPeriodId) {
            $query->where('period_id', $selectedPeriodId);
        }

        if ($request->filled('q1_punya_rtkd')) {
            if ($request->input('q1_punya_rtkd') === 'ya') {
                $query->whereNotNull('rtk_document_id');
            } else {
                $query->whereNull('rtk_document_id');
            }
        }

        if ($request->filled('q2_jadi_acuan')) {
            $query->where('q2_jadi_acuan', $request->input('q2_jadi_acuan'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('scopeArea.province', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $submissions = $query->latest()->paginate(20)->withQueryString();

        $overriddenUserIds = [];
        if ($selectedPeriodId) {
            $overriddenUserIds = RtkPemanfaatanSubmission::where('period_id', $selectedPeriodId)
                ->whereColumn('created_by', '!=', 'user_id')
                ->pluck('user_id')
                ->toArray();
        }

        return view('rtk::adminPusat.hasil-pemanfaatan.index', compact('submissions', 'periods', 'selectedPeriodId', 'overriddenUserIds'));
    }

    public function show($id)
    {
        $submission = RtkPemanfaatanSubmission::with(['user', 'period', 'rtkDocument'])->findOrFail($id);
        
        $isOverridden = false;
        if ($submission->created_by === $submission->user_id) {
            $isOverridden = RtkPemanfaatanSubmission::where('user_id', $submission->user_id)
                ->where('period_id', $submission->period_id)
                ->where('created_by', '!=', $submission->user_id)
                ->exists();
        }
        
        return view('rtk::adminPusat.hasil-pemanfaatan.show', compact('submission', 'isOverridden'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'verifications' => 'required|array',
            'verifications.*.status' => 'required|in:verified,rejected',
            'verifications.*.catatan' => 'nullable|string',
        ]);

        $submission = RtkPemanfaatanSubmission::findOrFail($id);
        
        $verifications = $request->input('verifications');
        
        $hasRejection = false;
        foreach ($verifications as $field => $data) {
            if ($data['status'] === 'rejected') {
                $hasRejection = true;
                break;
            }
        }

        $submission->field_verifications = $verifications;
        $submission->status_verifikasi = $hasRejection ? 'rejected' : 'verified';
        
        $submission->catatan_verifikasi = null; 
        
        $submission->save();

        $statusMsg = $submission->status_verifikasi === 'verified' 
            ? 'Kuesioner berhasil disetujui sepenuhnya.' 
            : 'Kuesioner dikembalikan ke provinsi untuk direvisi pada bagian tertentu.';

        ToastMagic::success($statusMsg);

        return redirect()->route('admin-pusat.hasil-pemanfaatan-rtkd.index');
    }

    public function editOnBehalf($id)
    {
        $submission = RtkPemanfaatanSubmission::with(['user', 'period'])->findOrFail($id);
        $targetUser = $submission->user;
        
        $latestRtk = null;
        if ($targetUser->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', $targetUser->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->berlaku()
                ->latest()
                ->first();
        }

        return view('rtk::adminPusat.hasil-pemanfaatan.edit-on-behalf', [
            'activePeriod' => $submission->period,
            'latestRtk' => $latestRtk,
            'submission' => $submission,
            'targetUser' => $targetUser
        ]);
    }

    public function storeOnBehalf(Request $request, $id)
    {
        $data = RtkPemanfaatanSubmission::findOrFail($id);
        $targetUser = User::findOrFail($data->user_id);
        
        $latestRtk = null;
        if ($targetUser->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', $targetUser->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->berlaku()
                ->latest()
                ->first();
        }

        $data->created_by = auth()->id();
        $this->processFormData($request, $data, $latestRtk);

        $data->status_verifikasi = 'pending';
        $data->catatan_verifikasi = 'Diisi/Dikoreksi langsung oleh Admin Pusat.';
        $data->save();

        ToastMagic::success('Kuesioner berhasil diperbarui oleh Admin Pusat.');
        return redirect()->route('admin-pusat.hasil-pemanfaatan-rtkd.index');
    }

    private function processFormData(Request $request, RtkPemanfaatanSubmission $data, ?RencanaTenagaKerja $latestRtk)
    {
        if ($latestRtk) {
            $data->rtk_document_id = $latestRtk->id;
        } else {
            $data->rtk_document_id = null;
        }

        if (!$data->rtk_document_id) {
            $data->q2_jadi_acuan = null;
            $data->dokumen_acuan = null;
            $data->komponen_acuan = null;
            $data->alasan_belum_acuan = null;

            $alasanTidakPunya = [];
            if ($request->has('alasan_tidak_punya')) {
                foreach ($request->input('alasan_tidak_punya') as $alasan) {
                    $keterangan = ($alasan === 'Lainnya') ? $request->input('alasan_tidak_punya_lainnya') : null;
                    $alasanTidakPunya[] = [
                        'alasan' => $alasan,
                        'keterangan_lainnya' => $keterangan
                    ];
                }
            }
            $data->alasan_tidak_punya = $alasanTidakPunya;
        } 
        else {
            $data->alasan_tidak_punya = null;
            $data->q2_jadi_acuan = $request->input('q2_jadi_acuan', 'tidak');

            if ($data->q2_jadi_acuan === 'ya') {
                $data->alasan_belum_acuan = null;

                $dokumenAcuan = [];
                if ($request->has('dokumen_acuan')) {
                    foreach ($request->input('dokumen_acuan') as $docType) {
                        $namaLainnya = ($docType === 'lainnya') ? $request->input('dokumen_acuan_lainnya') : null;
                        $dokumenAcuan[] = [
                            'doc_type' => $docType,
                            'nama_lainnya' => $namaLainnya
                        ];
                    }
                }
                $data->dokumen_acuan = $dokumenAcuan;

                $komponenAcuan = [];
                if ($request->has('dokumen_acuan')) {
                    foreach ($request->input('dokumen_acuan') as $docType) {
                        $komps = $request->input("komponen_{$docType}", []);
                        $halms = $request->input("halaman_{$docType}", []);
                        $ketLain = $request->input("lainnya_{$docType}");
                        
                        foreach ($komps as $komp) {
                            $komponenAcuan[] = [
                                'doc_type' => $docType,
                                'komponen' => $komp,
                                'halaman_acuan' => $halms[$komp] ?? null,
                                'keterangan_lainnya' => ($komp === 'Lainnya') ? $ketLain : null
                            ];
                        }
                    }
                }
                $data->komponen_acuan = $komponenAcuan;

                $uploads = $data->dokumen_uploads ?? [];
                $uploadsByDocType = [];
                foreach ($uploads as $u) {
                    $uploadsByDocType[$u['doc_type']] = $u;
                }

                if ($request->has('dokumen_acuan')) {
                    foreach ($request->input('dokumen_acuan') as $docType) {
                        $fileInputName = "upload_{$docType}";
                        if ($request->hasFile($fileInputName)) {
                            $file = $request->file($fileInputName);
                            if ($file->isValid()) {
                                $path = $file->store('rtkd-pemanfaatan/uploads', 'public');
                                $uploadsByDocType[$docType] = [
                                    'doc_type' => $docType,
                                    'file_path' => $path,
                                    'original_name' => $file->getClientOriginalName()
                                ];
                            }
                        }
                    }
                    
                    $newUploads = [];
                    foreach ($request->input('dokumen_acuan') as $docType) {
                        if (isset($uploadsByDocType[$docType])) {
                            $newUploads[] = $uploadsByDocType[$docType];
                        }
                    }
                    $uploads = $newUploads;
                } else {
                    $uploads = [];
                }
                $data->dokumen_uploads = $uploads;

            } 
            else {
                $data->dokumen_acuan = null;
                $data->komponen_acuan = null;
                $data->dokumen_uploads = null;

                $alasanBelumAcuan = [];
                if ($request->has('alasan_belum_acuan')) {
                    foreach ($request->input('alasan_belum_acuan') as $alasan) {
                        $keterangan = ($alasan === 'Lainnya') ? $request->input('alasan_belum_acuan_lainnya') : null;
                        $alasanBelumAcuan[] = [
                            'alasan' => $alasan,
                            'keterangan_lainnya' => $keterangan
                        ];
                    }
                }
                $data->alasan_belum_acuan = $alasanBelumAcuan;
            }
        }
    }
}
