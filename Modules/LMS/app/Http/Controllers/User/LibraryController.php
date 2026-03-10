<?php

namespace Modules\LMS\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Models\Library;
use Modules\LMS\Models\LibraryType;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type'); // slug of the library type

        $query = Library::with('libraryType')->where('is_active', true);

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($type) {
            $query->whereHas('libraryType', function ($q) use ($type) {
                $q->where('slug', $type);
            });
        }

        $libraries = $query->latest()->paginate(12);
        
        // Fetch types for the filter tabs (only those that actually have active libraries might be ideal, but we'll fetch all active types)
        $libraryTypes = LibraryType::orderBy('name')->get();

        return view('lms::user.library.index', compact('libraries', 'libraryTypes', 'type', 'search'));
    }
}
