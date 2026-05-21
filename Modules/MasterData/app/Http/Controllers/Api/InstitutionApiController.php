<?php

namespace Modules\MasterData\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Models\Institution;

class InstitutionApiController extends Controller
{
    /**
     * Get a list of active institutions based on type.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        
        $query = Institution::where('is_active', true);
        
        if ($type && in_array($type, ['pusat', 'daerah'])) {
            $query->where('type', $type);
        }
        
        // Order by name ascending
        $institutions = $query->orderBy('name', 'asc')->get(['id', 'name', 'type']);
        
        return response()->json([
            'success' => true,
            'data' => $institutions
        ]);
    }
}
