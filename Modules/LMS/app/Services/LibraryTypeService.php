<?php

namespace Modules\LMS\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\LMS\Models\LibraryType;

class LibraryTypeService
{
    public function paginateFiltered(?string $search = null, int $limit = 10): LengthAwarePaginator
    {
        return LibraryType::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate($limit)
            ->withQueryString();
    }

    public function createLibraryType(array $data): LibraryType
    {
        $data['slug'] = Str::slug($data['name']);

        return LibraryType::create($data);
    }

    public function updateLibraryType(LibraryType $libraryType, array $data): LibraryType
    {
        $data['slug'] = Str::slug($data['name']);
        $libraryType->update($data);

        return $libraryType;
    }

    public function deleteLibraryType(LibraryType $libraryType): void
    {
        $libraryType->delete();
    }
}
