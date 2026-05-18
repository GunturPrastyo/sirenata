<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Models\RencanaTenagaKerja;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\StatusDocument;
use Modules\RTK\Services\ApprovalRtkService;

class RTKApprovalProvinceController extends Controller
{
    public function __construct(
        private ApprovalRtkService $rtkService
    ) {}

    public function approveVerificationKabKota(Request $request, RencanaTenagaKerja $rtk)
    {
        $result = $this->rtkService->approveVerificationKabKota(rtk: $rtk);
        if (! $result['success']) ToastMagic::error($result['message']);
        return back();
    }

    public function approveDocumentKabKota(Request $request, RencanaTenagaKerja $rtk)
    {
        $result = $this->rtkService->approveDocumentKabKota(rtk: $rtk);
        if (! $result['success']) ToastMagic::error($result['message']);
        return back();
    }

    public function rejectKabKota(Request $request, RencanaTenagaKerja $rtk)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500']
        ]);

        // Tidak bisa reject kalau RTK sudah berlaku penuh
        if ($rtk->is_berlaku) {
            ToastMagic::error('RTK yang sedang berlaku tidak bisa ditolak.');
            return back();
        }

        // Hanya bisa reject kalau is_active = true dan status_document = NA
        $bolehReject = $rtk->is_active
            && $rtk->status_document === StatusDocument::NA
            && in_array($rtk->status_verification, [
                RTKStatusVerification::PENDING,
                RTKStatusVerification::APPROVED,
            ]);

        if (! $bolehReject) {
            ToastMagic::error('RTK ini tidak bisa ditolak.');
            return back();
        }

        $rtk->update([
            'status_verification' => RTKStatusVerification::REJECTED->value,
            'status_document'     => StatusDocument::NA->value,
            // is_active tidak diubah — tetap true agar user bisa edit
            'rejected_reason'     => $validated['reason'],
            'approved_by'         => Auth::id(),
            'approved_at'         => now(),
        ]);

        ToastMagic::success('RTK berhasil ditolak.');
        return back();
    }
}
