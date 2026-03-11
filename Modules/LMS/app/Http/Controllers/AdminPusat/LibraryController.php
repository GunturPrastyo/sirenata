<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Models\Library;
use Modules\LMS\Models\LibraryType;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $libraryTypeId = $request->input('library_type_id');

        $query = Library::with(['libraryType', 'creator']);

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($libraryTypeId) {
            $query->where('library_type_id', $libraryTypeId);
        }

        $libraries = $query->latest()->paginate(10);
        $libraryTypes = LibraryType::orderBy('name')->get();

        return view('lms::admin-pusat.libraries.index', compact('libraries', 'search', 'libraryTypeId', 'libraryTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'library_type_id' => 'required|exists:library_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'file_path' => 'nullable|file|mimes:pdf|max:20480',
            'video_path' => 'nullable|file|mimes:mp4,webm|max:51200',
            'external_link' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('libraries/covers', 'public');
        }

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('libraries/files', 'public');
        }

        if ($request->hasFile('video_path')) {
            $validated['video_path'] = $request->file('video_path')->store('libraries/videos', 'public');
        }

        $validated['created_by'] = auth()->id();

        Library::create($validated);

        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $library = Library::findOrFail($id);

        $validated = $request->validate([
            'library_type_id' => 'required|exists:library_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'file_path' => 'nullable|file|mimes:pdf|max:20480',
            'video_path' => 'nullable|file|mimes:mp4,webm|max:51200',
            'external_link' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($library->cover_image) {
                Storage::disk('public')->delete($library->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('libraries/covers', 'public');
        }

        if ($request->hasFile('file_path')) {
            if ($library->file_path) {
                Storage::disk('public')->delete($library->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('libraries/files', 'public');
        }

        if ($request->hasFile('video_path')) {
            if ($library->video_path) {
                Storage::disk('public')->delete($library->video_path);
            }
            $validated['video_path'] = $request->file('video_path')->store('libraries/videos', 'public');
        }

        $library->update($validated);

        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $library = Library::findOrFail($id);

        if ($library->cover_image) {
            Storage::disk('public')->delete($library->cover_image);
        }

        if ($library->file_path) {
            Storage::disk('public')->delete($library->file_path);
        }

        if ($library->video_path) {
            Storage::disk('public')->delete($library->video_path);
        }

        $library->delete();

        return redirect()->route('admin-pusat.libraries.index')
            ->with('success', 'Materi Perpustakaan berhasil dihapus.');
    }
}
