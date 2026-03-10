<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Models\LibraryType;
use Illuminate\Support\Str;

class LibraryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = LibraryType::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $libraryTypes = $query->latest()->paginate(10);
        return view('lms::admin-pusat.library-types.index', compact('libraryTypes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms::admin-pusat.library-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:library_types,name',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        LibraryType::create($validated);

        return redirect()->route('admin-pusat.library-types.index')
            ->with('success', 'Tipe Perpustakaan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $libraryType = LibraryType::findOrFail($id);
        return view('lms::admin-pusat.library-types.edit', compact('libraryType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) 
    {
        $libraryType = LibraryType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:library_types,name,' . $libraryType->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $libraryType->update($validated);

        return redirect()->route('admin-pusat.library-types.index')
            ->with('success', 'Tipe Perpustakaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) 
    {
        $libraryType = LibraryType::findOrFail($id);
        $libraryType->delete();

        return redirect()->route('admin-pusat.library-types.index')
            ->with('success', 'Tipe Perpustakaan berhasil dihapus.');
    }
}
