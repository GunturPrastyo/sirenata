<?php

namespace Modules\MasterData\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Models\Institution;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $institutions = Institution::where('type', 'daerah');
        
        // Search filter
        if ($request->has('search')) {
            $institutions->where('name', 'like', '%' . $request->search . '%');
        }
        
        $institutions = $institutions->orderBy('name')->paginate(15);
        
        return view('masterdata::super-admin.instansi.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['type'] = 'daerah'; // Hardcode type
        $data['is_active'] = $request->has('is_active') ? true : false;

        Institution::create($data);

        ToastMagic::success("Instansi Daerah berhasil ditambahkan!");
        return redirect()->route('super-admin.instansi.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institution $instansi)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institution $instansi)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['type'] = 'daerah'; // Hardcode type just in case
        $data['is_active'] = $request->has('is_active') ? true : false;

        $instansi->update($data);

        ToastMagic::success("Instansi Daerah berhasil diperbarui!");
        return redirect()->route('super-admin.instansi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $instansi)
    {
        $instansi->delete();
        
        ToastMagic::success("Instansi Daerah berhasil dihapus!");
        return redirect()->route('super-admin.instansi.index');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Institution $instansi)
    {
        $instansi->update([
            'is_active' => !$instansi->is_active
        ]);

        $statusText = $instansi->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ToastMagic::success("Instansi Daerah berhasil {$statusText}!");
        return redirect()->back();
    }
}
