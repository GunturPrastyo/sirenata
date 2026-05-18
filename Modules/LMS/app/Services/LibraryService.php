<?php

namespace Modules\LMS\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\LMS\Models\Library;
use Modules\LMS\Models\LibraryCategory;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class LibraryService
{
    public function getFilteredQueryBuilder(
        ?string $search = null,
        ?string $libraryCategoryId = null,
        ?string $libraryCategoryName = null
    ) {
        return Library::with(['libraryCategory', 'creator'])
            ->when($search, fn($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($libraryCategoryId, fn($query) => $query->where('library_category_id', $libraryCategoryId))
            ->when($libraryCategoryName, fn($query) => $query->whereHas('libraryCategory', function ($q) use ($libraryCategoryName) {
                $q->where('name', $libraryCategoryName);
            }))
            ->latest();
    }

    public function paginateFiltered(
        ?string $search = null,
        ?string $libraryCategoryId = null,
        ?string $libraryCategoryName = null,
        int $limit = 10
    ) {
        return $this->getFilteredQueryBuilder($search, $libraryCategoryId, $libraryCategoryName)
            ->paginate($limit)
            ->withQueryString();
    }

    public function createLibrary(array $data): Library
    {
        return DB::transaction(function () use ($data) {

            $coverImage = null;
            if (!empty($data['cover_image'])) {
                $coverImage = $data['cover_image']->store('libraries/covers', 'public');
            }

            $filePath = null;
            if (!empty($data['file_path'])) {
                $filePath = $data['file_path']->store('libraries/files', 'public');
            }

            $videoPath = null;
            if (!empty($data['video_path'])) {
                $videoPath = $data['video_path']->store('libraries/videos', 'public');
            }

            $library = Library::create([
                'library_category_id' => $data['library_category_id'],
                'title'           => $data['title'],
                'description'     => $data['description'] ?? null,
                'cover_image'     => $coverImage,
                'file_path'       => $filePath,
                'video_path'      => $videoPath,
                'external_link'   => $data['external_link'] ?? null,
                'created_by'      => Auth::id(),
            ]);

            ToastMagic::success('Materi Perpustakaan berhasil ditambahkan!');

            return $library;
        });
    }

    public function updateLibrary(Library $library, array $data): Library
    {
        return DB::transaction(function () use ($library, $data) {

            $coverImage = $library->cover_image;
            if (!empty($data['cover_image'])) {
                if ($library->cover_image) {
                    Storage::disk('public')->delete($library->cover_image);
                }
                $coverImage = $data['cover_image']->store('libraries/covers', 'public');
            }

            $filePath = $library->file_path;
            if (!empty($data['file_path'])) {
                if ($library->file_path) {
                    Storage::disk('public')->delete($library->file_path);
                }
                $filePath = $data['file_path']->store('libraries/files', 'public');
            }

            $videoPath = $library->video_path;
            if (!empty($data['video_path'])) {
                if ($library->video_path) {
                    Storage::disk('public')->delete($library->video_path);
                }
                $videoPath = $data['video_path']->store('libraries/videos', 'public');
            }

            $library->update([
                'library_category_id' => $data['library_category_id'],
                'title'           => $data['title'],
                'description'     => $data['description'] ?? null,
                'cover_image'     => $coverImage,
                'file_path'       => $filePath,
                'video_path'      => $videoPath,
                'external_link'   => $data['external_link'] ?? null,
            ]);

            ToastMagic::success('Materi Perpustakaan berhasil diperbarui!');

            return $library;
        });
    }

    public function deleteLibrary(Library $library): void
    {
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

        ToastMagic::success('Materi Perpustakaan berhasil dihapus!');
    }
}
