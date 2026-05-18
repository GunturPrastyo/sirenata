<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Http\Requests\LibraryStoreRequest;
use Modules\LMS\Http\Requests\LibraryUpdateRequest;
use Modules\LMS\Models\Library;
use Modules\LMS\Models\LibraryCategory;
use Modules\LMS\Services\LibraryService;

class LibraryController extends Controller
{
    public function __construct(
        private LibraryService $libraryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $libraryCategoryId = $request->input('library_category_id');

        $libraries = $this->libraryService->paginateFiltered($search, $libraryCategoryId);
        $libraryCategories = LibraryCategory::orderBy('name')->get();

        return view('lms::admin-pusat.libraries.index', compact('libraries', 'search', 'libraryCategoryId', 'libraryCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $libraryCategories = LibraryCategory::orderBy('name')->get();
        return view('lms::admin-pusat.libraries.create', compact('libraryCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LibraryStoreRequest $request)
    {
        $this->libraryService->createLibrary($request->validated());
        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Library $library)
    {
        $libraryCategories = LibraryCategory::orderBy('name')->get();
        return view('lms::admin-pusat.libraries.edit', compact('library', 'libraryCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LibraryUpdateRequest $request, Library $library)
    {
        $this->libraryService->updateLibrary($library, $request->validated());
        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Library $library)
    {
        $this->libraryService->deleteLibrary($library);
        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil dihapus.');
    }
}
