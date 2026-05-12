<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Http\Requests\LibraryStoreRequest;
use Modules\LMS\Http\Requests\LibraryUpdateRequest;
use Modules\LMS\Models\Library;
use Modules\LMS\Models\LibraryCategory;
use Modules\LMS\Services\LibraryService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class LibraryController extends Controller implements HasMiddleware
{
    public function __construct(
        private LibraryService $libraryService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('library-view|library-create|library-edit|library-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('library-create'), only: ['store']),
            new Middleware(PermissionMiddleware::using('library-edit'), only: ['update']),
            new Middleware(PermissionMiddleware::using('library-delete'), only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $libraryCategoryId = $request->input('library_category_id');

        $libraries = $this->libraryService->paginateFiltered(
            search: $search,
            libraryCategoryId: $libraryCategoryId ? (int) $libraryCategoryId : null,
        );
        $libraryCategories = LibraryCategory::orderBy('name')->get();

        return view('lms::admin-pusat.libraries.index', compact('libraries', 'search', 'libraryCategoryId', 'libraryCategories'));
    }

    public function store(LibraryStoreRequest $request)
    {
        $this->libraryService->createLibrary($request->validated());
        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil ditambahkan.');
    }

    public function update(LibraryUpdateRequest $request, string $id)
    {
        $library = Library::findOrFail($id);
        $this->libraryService->updateLibrary($library, $request->validated());
        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $library = Library::findOrFail($id);
        $this->libraryService->deleteLibrary($library);
        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil dihapus.');
    }
}
