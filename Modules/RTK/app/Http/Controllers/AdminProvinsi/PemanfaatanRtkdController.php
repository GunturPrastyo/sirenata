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
    /**
     * Tampilkan status pemanfaatan RTKD untuk periode aktif.
     */
    public function index()
    {
        $user = Auth::user();
        $activePeriod = RtkSurveyPeriod::aktif()->first();

        // Ambil semua riwayat pengisian kuesioner user ini
        $submissions = RtkPemanfaatanSubmission::with('period')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Tentukan data mana yang akan ditampilkan detailnya di bawah tabel
        $detailSubmission = null;

        if ($activePeriod) {
            // Cari kiriman di periode aktif ini saja
            $currentPeriodSubmissions = $submissions->where('period_id', $activePeriod->id);

            if ($currentPeriodSubmissions->isNotEmpty()) {
                // Prioritas 1: Yang sudah diverifikasi (Verified)
                $detailSubmission = $currentPeriodSubmissions->where('status_verifikasi', 'verified')->first();

                // Prioritas 2: Jika belum ada yang verified, ambil yang terbaru dari periode ini
                if (!$detailSubmission) {
                    $detailSubmission = $currentPeriodSubmissions->first();
                }
            }
        }

        // Tentukan data untuk kuesioner aktif (untuk tombol ajakan isi di tabel/banner)
        $activeSubmission = $activePeriod ? $submissions->where('period_id', $activePeriod->id)->first() : null;

        return view('rtk::adminProvince.pemanfaatan-rtkd.index', [
            'activePeriod' => $activePeriod,
            'submissions' => $submissions,
            'detailSubmission' => $detailSubmission,
            'activeSubmission' => $activeSubmission,
        ]);
    }

    /**
     * Tampilkan form kuesioner dinamis.
     */
    public function create()
    {
        $user = Auth::user();
        $activePeriod = RtkSurveyPeriod::aktif()->first();

        if (!$activePeriod) {
            ToastMagic::error('Tidak ada periode survei yang sedang aktif saat ini.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        // Cek apakah sudah mengisi
        $existing = RtkPemanfaatanSubmission::where('user_id', $user->id)
            ->where('period_id', $activePeriod->id)
            ->first();

        if ($existing) {
            return redirect()->route('admin-province.pemanfaatan-rtkd.edit', $existing->id);
        }

        // Cek data RTK Provinsi aktif
        // Ambil data terbaru yang statusnya disetujui (atau di sini mungkin menggunakan is_active)
        $latestRtk = null;
        if ($user->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', $user->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        return view('rtk::adminProvince.pemanfaatan-rtkd.form', [
            'activePeriod' => $activePeriod,
            'latestRtk' => $latestRtk,
            'submission' => new RtkPemanfaatanSubmission() // Kosong untuk mode create
        ]);
    }

    /**
     * Simpan jawaban kuesioner.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $activePeriod = RtkSurveyPeriod::aktif()->firstOrFail();

        // Cek double submission
        if (RtkPemanfaatanSubmission::where('user_id', $user->id)->where('period_id', $activePeriod->id)->exists()) {
            ToastMagic::error('Anda sudah mengisi kuesioner untuk periode ini.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        // Cek RTK Provinsi aktif untuk auto-fill
        $latestRtk = null;
        if ($user->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', $user->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        $data = new RtkPemanfaatanSubmission();
        $data->user_id = $user->id;
        $data->period_id = $activePeriod->id;

        $this->processFormData($request, $data, $latestRtk);

        ToastMagic::success('Kuesioner berhasil disimpan.');
        return redirect()->route('admin-province.pemanfaatan-rtkd.index');
    }

    /**
     * Edit jawaban kuesioner (jika belum diverifikasi).
     */
    public function edit(RtkPemanfaatanSubmission $pemanfaatan_rtkd)
    {
        if ($pemanfaatan_rtkd->user_id !== Auth::id()) {
            abort(403);
        }

        // Jika sudah diverifikasi tidak bisa diedit
        if ($pemanfaatan_rtkd->status_verifikasi === 'verified') {
            ToastMagic::error('Kuesioner yang sudah disetujui tidak dapat diedit.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        $activePeriod = RtkSurveyPeriod::aktif()->first();
        
        $latestRtk = null;
        if (Auth::user()->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', Auth::user()->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        return view('rtk::adminProvince.pemanfaatan-rtkd.form', [
            'activePeriod' => $activePeriod,
            'latestRtk' => $latestRtk,
            'submission' => $pemanfaatan_rtkd
        ]);
    }

    /**
     * Update jawaban kuesioner.
     */
    public function update(Request $request, RtkPemanfaatanSubmission $pemanfaatan_rtkd)
    {
        if ($pemanfaatan_rtkd->user_id !== Auth::id()) {
            abort(403);
        }

        if ($pemanfaatan_rtkd->status_verifikasi === 'verified') {
            ToastMagic::error('Kuesioner yang sudah disetujui tidak dapat diedit.');
            return redirect()->route('admin-province.pemanfaatan-rtkd.index');
        }

        $latestRtk = null;
        if (Auth::user()->hasCompleteScope()) {
            $latestRtk = RencanaTenagaKerja::where('province_code', Auth::user()->scopeArea->province_code)
                ->where('type', \Modules\RTK\Enums\TypeRtk::PROVINSI)
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        $this->processFormData($request, $pemanfaatan_rtkd, $latestRtk);

        ToastMagic::success('Kuesioner berhasil diperbarui.');
        return redirect()->route('admin-province.pemanfaatan-rtkd.index');
    }

    /**
     * Helper untuk memproses form data JSON.
     */
    private function processFormData(Request $request, RtkPemanfaatanSubmission $data, ?RencanaTenagaKerja $latestRtk)
    {
        // 1. Auto-fill data RTKD dari sistem utama
        if ($latestRtk) {
            $data->q1_punya_rtkd = 'ya';
            $data->tahun_dari = $latestRtk->start_date;
            $data->tahun_sampai = $latestRtk->end_date;
            $data->rtk_document_id = $latestRtk->id;
        } else {
            $data->q1_punya_rtkd = 'tidak';
            $data->tahun_dari = null;
            $data->tahun_sampai = null;
            $data->rtk_document_id = null;
        }

        // 2. Jika Tidak Punya RTKD
        if ($data->q1_punya_rtkd === 'tidak') {
            $data->q2_jadi_acuan = null;
            $data->dokumen_acuan = null;
            $data->komponen_acuan = null;
            $data->alasan_belum_acuan = null;

            // Proses alasan tidak punya
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
        // 3. Jika Punya RTKD
        else {
            $data->alasan_tidak_punya = null;
            $data->q2_jadi_acuan = $request->input('q2_jadi_acuan', 'tidak');

            // 3A. Jika jadi acuan
            if ($data->q2_jadi_acuan === 'ya') {
                $data->alasan_belum_acuan = null;

                // Dokumen acuan
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

                // Komponen acuan
                $komponenAcuan = [];
                if ($request->has('dokumen_acuan')) {
                    foreach ($request->input('dokumen_acuan') as $docType) {
                        $komps = $request->input("komponen_{$docType}", []);
                        $halms = $request->input("halaman_{$docType}", []);
                        $ketLain = $request->input("lainnya_{$docType}");
                        
                        foreach ($komps as $komp) {
                            // Map 'Angka Pengangguran' to safe key for array index in HTML, usually we can just use the exact string if we use bracket notation in HTML like name="halaman_rpjmd[Angka Pengangguran]"
                            // But for safety, in HTML we'll just use the exact string.
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

                // Proses file upload tambahan (jika ada) per dokumen
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
                    
                    // Filter: hanya simpan upload untuk dokumen yang dicentang
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
            // 3B. Jika tidak jadi acuan
            else {
                $data->dokumen_acuan = null;
                $data->komponen_acuan = null;
                $data->dokumen_uploads = null;

                // Alasan belum acuan
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

        $data->status_verifikasi = 'pending'; // Reset status setiap kali diupdate
        $data->save();
    }
}
