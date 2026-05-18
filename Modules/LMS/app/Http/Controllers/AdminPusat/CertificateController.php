<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\LMS\Models\CertificateSetting;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = CertificateSetting::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('signer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('signer_title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $settings = $query->latest()->paginate($request->input('per_page', 10));

        return view('lms::admin-pusat.certificates.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'signer_name' => 'required|string|max:255',
            'signer_title' => 'required|string|max:255',
            'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $setting = new CertificateSetting();
        $setting->signer_name = $validated['signer_name'];
        $setting->signer_title = $validated['signer_title'];
        $setting->is_active = false;

        if ($request->hasFile('signature_image')) {
            $setting->signature_image = $request->file('signature_image')
                ->store('certificates/signatures', 'public');
        }

        $setting->save();

        ToastMagic::success('Tanda tangan sertifikat berhasil ditambahkan.');

        return redirect()->route('admin-pusat.certificates.index');
    }

    public function update(Request $request, CertificateSetting $certificate)
    {
        $validated = $request->validate([
            'signer_name' => 'required|string|max:255',
            'signer_title' => 'required|string|max:255',
            'signature_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $certificate->signer_name = $validated['signer_name'];
        $certificate->signer_title = $validated['signer_title'];

        if ($request->hasFile('signature_image')) {
            if ($certificate->signature_image) {
                Storage::disk('public')->delete($certificate->signature_image);
            }
            $certificate->signature_image = $request->file('signature_image')
                ->store('certificates/signatures', 'public');
        }

        $certificate->save();

        ToastMagic::success('Tanda tangan sertifikat berhasil diperbarui.');

        return redirect()->route('admin-pusat.certificates.index');
    }

    public function activate(CertificateSetting $certificate)
    {
        CertificateSetting::where('is_active', true)->update(['is_active' => false]);

        $certificate->is_active = true;
        $certificate->save();

        ToastMagic::success('Tanda tangan berhasil diaktifkan.');

        return redirect()->route('admin-pusat.certificates.index');
    }

    public function destroy(CertificateSetting $certificate)
    {
        if ($certificate->signature_image) {
            Storage::disk('public')->delete($certificate->signature_image);
        }

        $certificate->delete();

        ToastMagic::success('Tanda tangan sertifikat berhasil dihapus.');

        return redirect()->route('admin-pusat.certificates.index');
    }
}
