<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Models\RtkPemanfaatanSubmission;
use Modules\RTK\Models\RtkSurveyPeriod;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class PemanfaatanRtkdController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activePeriod = RtkSurveyPeriod::aktif()->first();

        $submissions = collect();
        if ($activePeriod) {
            $submissions = RtkPemanfaatanSubmission::where('user_id', $user->id)
                ->where('period_id', $activePeriod->id)
                ->latest()
                ->get();
        }

        return view('rtk::adminProvince.pemanfaatan-rtkd.index', [
            'activePeriod' => $activePeriod,
            'submissions' => $submissions,
            'submission' => $submissions->first()
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $activePeriod = RtkSurveyPeriod::aktif()->first();

        if (!$activePeriod) {
            ToastMagic::error('Tidak ada periode survei yang sedang aktif saat ini.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        if ($activePeriod->tanggal_selesai && now()->gt($activePeriod->tanggal_selesai->endOfDay())) {
            ToastMagic::error('Batas waktu pengisian kuesioner periode ini telah berakhir.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        $existing = RtkPemanfaatanSubmission::where('user_id', $user->id)
            ->where('period_id', $activePeriod->id)
            ->where('created_by', $user->id)
            ->first();

        $pusatSubmission = RtkPemanfaatanSubmission::where('user_id', $user->id)
            ->where('period_id', $activePeriod->id)
            ->where('created_by', '!=', $user->id)
            ->first();

        if ($pusatSubmission) {
            ToastMagic::error('Data telah dikoreksi/diisi oleh Admin Pusat dan tidak dapat diubah lagi.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        if ($existing) {
            return redirect()->route('admin-province.pemanfaatan-rtkd.edit', $existing->id);
        }

        $latestRtk = null;
        if ($user->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', $user->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->berlaku()
                ->latest()
                ->first();
        }

        return view('rtk::adminProvince.pemanfaatan-rtkd.form', [
            'activePeriod' => $activePeriod,
            'latestRtk' => $latestRtk,
            'submission' => new RtkPemanfaatanSubmission()
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $activePeriod = RtkSurveyPeriod::aktif()->firstOrFail();

        if ($activePeriod->tanggal_selesai && now()->gt($activePeriod->tanggal_selesai->endOfDay())) {
            ToastMagic::error('Batas waktu pengisian kuesioner periode ini telah berakhir.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        if (RtkPemanfaatanSubmission::where('user_id', $user->id)->where('period_id', $activePeriod->id)->where('created_by', $user->id)->exists()) {
            ToastMagic::error('Anda sudah mengisi kuesioner untuk periode ini.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        $pusatSubmission = RtkPemanfaatanSubmission::where('user_id', $user->id)
            ->where('period_id', $activePeriod->id)
            ->where('created_by', '!=', $user->id)
            ->first();

        if ($pusatSubmission) {
            ToastMagic::error('Data telah dikoreksi/diisi oleh Admin Pusat dan tidak dapat diubah lagi.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        $latestRtk = null;
        if ($user->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', $user->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->berlaku()
                ->latest()
                ->first();
        }

        $data = new RtkPemanfaatanSubmission();
        $data->user_id = $user->id;
        $data->period_id = $activePeriod->id;
        $data->created_by = auth()->id();

        $this->processFormData($request, $data, $latestRtk);

        ToastMagic::success('Kuesioner berhasil disimpan.');
        return redirect()->route('admin-province.pemanfaatan-rtkd.index');
    }

    public function edit(RtkPemanfaatanSubmission $pemanfaatan_rtkd)
    {
        if ($pemanfaatan_rtkd->user_id !== Auth::id()) {
            abort(403);
        }

        $activePeriod = RtkSurveyPeriod::aktif()->first();

        if ($activePeriod && $activePeriod->tanggal_selesai && now()->gt($activePeriod->tanggal_selesai->endOfDay())) {
            ToastMagic::error('Batas waktu pengisian kuesioner periode ini telah berakhir.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }
        
        $latestRtk = null;
        if (Auth::user()->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', Auth::user()->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->berlaku()
                ->latest()
                ->first();
        }

        return view('rtk::adminProvince.pemanfaatan-rtkd.form', [
            'activePeriod' => $activePeriod,
            'latestRtk' => $latestRtk,
            'submission' => $pemanfaatan_rtkd
        ]);
    }

    public function update(Request $request, RtkPemanfaatanSubmission $pemanfaatan_rtkd)
    {
        if ($pemanfaatan_rtkd->user_id !== Auth::id()) {
            abort(403);
        }

        $activePeriod = RtkSurveyPeriod::aktif()->first();
        if ($activePeriod && $activePeriod->tanggal_selesai && now()->gt($activePeriod->tanggal_selesai->endOfDay())) {
            ToastMagic::error('Batas waktu pengisian kuesioner periode ini telah berakhir.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        $latestRtk = null;
        if (Auth::user()->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', Auth::user()->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->berlaku()
                ->latest()
                ->first();
        }

        $pemanfaatan_rtkd->created_by = Auth::id();
        $this->processFormData($request, $pemanfaatan_rtkd, $latestRtk);

        ToastMagic::success('Kuesioner berhasil diperbarui.');
        return redirect()->route('admin-province.pemanfaatan-rtkd.index');
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

        $data->status_verifikasi = 'pending';
        $data->save();
    }
}
