<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Models\LibraryCategory;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class LibraryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = LibraryCategory::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $libraryCategories = $query->latest()->paginate(10);
        return view('lms::admin-pusat.library-categories.index', compact('libraryCategories', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:library_categories,name',
            'description' => 'nullable|string',
        ]);

        LibraryCategory::create($validated);
        
        ToastMagic::success('Kategori Perpustakaan berhasil ditambahkan!');

        return redirect()->route('admin-pusat.library-categories.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) 
    {
        $libraryCategory = LibraryCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:library_categories,name,' . $libraryCategory->id,
            'description' => 'nullable|string',
        ]);

        $libraryCategory->update($validated);
        
        ToastMagic::success('Kategori Perpustakaan berhasil diperbarui!');

        return redirect()->route('admin-pusat.library-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) 
    {
        $libraryCategory = LibraryCategory::findOrFail($id);
        $libraryCategory->delete();
        
        ToastMagic::success('Kategori Perpustakaan berhasil dihapus!');

        return redirect()->route('admin-pusat.library-categories.index');
    }
}
