<?php

namespace Modules\RTK\Http\Controllers\AdminPusat\RtkNasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Services\RTKNService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\StatusDocument;

class RTKNApprovalController extends Controller
{
    public function __construct(
        private RTKNService $rtknService
    ) {}

    public function approveVerification(Request $request, RencanaTenagaKerja $rtkn)
    {
        $result = $this->rtknService->approveVerification(rtk: $rtkn);
        if (! $result['success']) ToastMagic::error($result['message']);
        return back();
    }

    public function approveDocument(Request $request, RencanaTenagaKerja $rtkn)
    {
        $result = $this->rtknService->approveDocument(rtk: $rtkn);
        if (! $result['success']) ToastMagic::error($result['message']);
        return back();
    }

    public function rejectRtkn(Request $request, RencanaTenagaKerja $rtkn)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500']
        ]);

        // Tidak bisa reject kalau RTK sudah berlaku penuh
        if ($rtkn->is_berlaku) {
            ToastMagic::error('RTKN yang sedang berlaku tidak bisa ditolak.');
            return back();
        }

        // Hanya bisa reject kalau is_active = true dan status_document = NA
        $bolehReject = $rtkn->is_active
            && $rtkn->status_document === StatusDocument::NA
            && in_array($rtkn->status_verification, [
                RTKStatusVerification::PENDING,
                RTKStatusVerification::APPROVED,
            ]);

        if (! $bolehReject) {
            ToastMagic::error('RTKN ini tidak bisa ditolak.');
            return back();
        }

        $rtkn->update([
            'status_verification' => RTKStatusVerification::REJECTED->value,
            'status_document'     => StatusDocument::NA->value,
            // is_active tidak diubah — tetap true agar user bisa edit
            'rejected_reason'     => $validated['reason'],
            'approved_by'         => Auth::id(),
            'approved_at'         => now(),
        ]);

        ToastMagic::success('RTKN berhasil ditolak.');
        return back();
    }
}
