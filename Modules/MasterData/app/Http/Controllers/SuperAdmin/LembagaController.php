<?php

namespace Modules\MasterData\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Models\Institution;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class LembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $institutions = Institution::where('type', 'pusat');
        
        // Search filter
        if ($request->has('search')) {
            $institutions->where('name', 'like', '%' . $request->search . '%');
        }
        
        $institutions = $institutions->orderBy('name')->paginate(15);
        
        return view('masterdata::super-admin.lembaga.index', compact('institutions'));
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
        $data['type'] = 'pusat'; // Hardcode type
        $data['is_active'] = $request->has('is_active') ? true : false;

        Institution::create($data);

        ToastMagic::success("Lembaga berhasil ditambahkan!");
        return redirect()->route('super-admin.lembaga.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institution $lembaga)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institution $lembaga)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['type'] = 'pusat'; // Hardcode type just in case
        $data['is_active'] = $request->has('is_active') ? true : false;

        $lembaga->update($data);

        ToastMagic::success("Lembaga berhasil diperbarui!");
        return redirect()->route('super-admin.lembaga.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $lembaga)
    {
        $lembaga->delete();
        
        ToastMagic::success("Lembaga berhasil dihapus!");
        return redirect()->route('super-admin.lembaga.index');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Institution $lembaga)
    {
        $lembaga->update([
            'is_active' => !$lembaga->is_active
        ]);

        $statusText = $lembaga->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ToastMagic::success("Lembaga berhasil {$statusText}!");
        return redirect()->back();
    }
}
