<?php

namespace Modules\MasterData\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Models\Institution;


class InstitutionApiController extends Controller
{
    /**
     * Get a list of active institutions based on type and regional filters.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        $provinceCode = $request->query('province_code');
        $regencyCode = $request->query('regency_code');
        $regionalLevel = $request->query('regional_level'); // Parameter baru penentu level daerah

        $query = Institution::where('is_active', true);

        if ($type && in_array($type, ['pusat', 'daerah'])) {
            $query->where('type', $type);
        }

        if ($type === 'daerah') {
            if ($regionalLevel === 'provinsi' && $provinceCode) {
                // HANYA tarik instansi provinsi (yang regency_code-nya KOSONG)
                $query->where('province_code', $provinceCode)
                    ->where(function ($q) {
                        $q->whereNull('regency_code')->orWhere('regency_code', '');
                    });
            } elseif ($regionalLevel === 'kabkota' && $regencyCode) {
                // HANYA tarik instansi spesifik kabupaten/kota tersebut
                $query->where('regency_code', $regencyCode);
            } else {
                // Fallback aman
                if ($regencyCode) {
                    $query->where('regency_code', $regencyCode);
                } elseif ($provinceCode) {
                    $query->where('province_code', $provinceCode);
                }
            }
        }

        $institutions = $query->orderBy('name', 'asc')->get(['id', 'name', 'type']);

        return response()->json([
            'success' => true,
            'data' => $institutions
        ]);
    }
}
