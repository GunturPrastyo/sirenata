<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;

class RTKApprovalPusatController extends Controller
{
    public function approveProvince(RencanaTenagaKerja $rtk)
    {
        DB::transaction(function () use ($rtk) {
            // nonaktifkan RTK lama yang approved
            RencanaTenagaKerja::where('province_code', $rtk->province_code)
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

    public function rejectProvince(Request $request, RencanaTenagaKerja $rtk)
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
