<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Models\RencanaTenagaKerja;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RTKApprovalProvinceController extends Controller
{
    public function approveKabKota(RencanaTenagaKerja $rtk)
    {
        DB::transaction(function () use ($rtk) {
            // nonaktifkan RTK lama yang approved
            RencanaTenagaKerja::where('province_code', $rtk->province_code)
                ->where('regency_code', $rtk->regency_code)
                ->where('type', $rtk->type)
                ->where('status', RTKStatus::APPROVED->value)
                ->where('id', '!=', $rtk->id)
                ->update([
                    'is_active' => false,
                    'status' => RTKStatus::EXPIRED->value,
                ]);

            // aktifkan RTK baru
            $rtk->update([
                'status' => RTKStatus::APPROVED->value,
                'is_active' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        });

        ToastMagic::success('RTK berhasil disetujui');
        return back();
    }

    public function rejectKabKota(Request $request, RencanaTenagaKerja $rtk)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500']
        ]);

        $rtk->update([
            'status' => RTKStatus::REJECTED->value,
            'rejected_reason' => $validated['reason'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        ToastMagic::success('RTK berhasil ditolak');
        return back();
    }
}
