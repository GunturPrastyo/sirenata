<?php

namespace Modules\LMS\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Models\LibraryCategory;
use Modules\LMS\Models\Library;
use Modules\LMS\Services\LibraryService;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

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

        // Statistik Nyata untuk Header
        $totalKoleksi = Library::count();
        $totalKategori = $libraryCategories->count();
        $totalDokumen = Library::whereNotNull('file_path')->count();
        $totalVideo = Library::where(function($q) {
            $q->whereNotNull('video_path')
              ->orWhere('external_link', 'like', '%youtube%')
              ->orWhere('external_link', 'like', '%youtu.be%');
        })->count();

        return view('lms::user.library.index', compact(
            'libraries', 
            'libraryCategories', 
            'type', 
            'search',
            'totalKoleksi',
            'totalKategori',
            'totalDokumen',
            'totalVideo'
        ));
    }

    /**
     * Display the specified resource and track access history.
     */
    public function show($id)
    {
        $library = Library::findOrFail($id);
        $userId = Auth::id();

        // Rekam jejak user jika sudah login
        if ($userId) {
            $this->libraryService->recordUserAccess($library, $userId);
        }

        // Tampilkan halaman detail atau langsung response file/video
        // Sesuaikan nama view ini dengan struktur folder Anda
        return view('lms::user.library.show', compact('library'));
    }
}