<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\LMS\Models\CertificateSetting;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificate signature settings.
     */
    public function index()
    {
        $settings = CertificateSetting::latest()->paginate(10);

        return view('lms::admin-pusat.certificates.index', compact('settings'));
    }

    /**
     * Store a newly created certificate setting.
     */
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

        return redirect()->route('admin-pusat.certificates.index')
            ->with('success', 'Tanda tangan sertifikat berhasil ditambahkan.');
    }

    /**
     * Update the specified certificate setting.
     */
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

        return redirect()->route('admin-pusat.certificates.index')
            ->with('success', 'Tanda tangan sertifikat berhasil diperbarui.');
    }

    /**
     * Set a certificate setting as active.
     */
    public function activate(CertificateSetting $certificate)
    {
        // Deactivate all
        CertificateSetting::where('is_active', true)->update(['is_active' => false]);

        // Activate selected
        $certificate->is_active = true;
        $certificate->save();

        return redirect()->route('admin-pusat.certificates.index')
            ->with('success', 'Tanda tangan berhasil diaktifkan.');
    }

    /**
     * Remove the specified certificate setting.
     */
    public function destroy(CertificateSetting $certificate)
    {
        if ($certificate->signature_image) {
            Storage::disk('public')->delete($certificate->signature_image);
        }

        $certificate->delete();

        return redirect()->route('admin-pusat.certificates.index')
            ->with('success', 'Tanda tangan sertifikat berhasil dihapus.');
    }
}
