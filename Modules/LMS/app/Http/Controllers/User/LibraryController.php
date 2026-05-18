<?php

namespace Modules\LMS\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $type = $request->input('type');

        $libraries = $this->libraryService->paginateFiltered(
            search: $search,
            libraryCategoryName: $type,
            limit: 12
        );

        $libraryCategories = LibraryCategory::orderBy('name')->get();

        return view('lms::user.library.index', compact('libraries', 'libraryCategories', 'type', 'search'));
    }
}
