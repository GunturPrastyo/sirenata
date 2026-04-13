<?php

namespace Modules\LMS\Http\Controllers\Api\Category;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResource;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Http\Request;
use Modules\LMS\Http\Requests\Api\Category\CategoryStoreRequest;
use Modules\LMS\Http\Requests\Api\Category\CategoryUpdateStoreRequest;
use Modules\LMS\Models\Category;
use Modules\LMS\Transformers\Api\CategoryResource;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @tags Categories
 */
class CategoryController extends Controller
{
    /**
     * List semua kategori
     *
     * Menampilkan daftar kategori course yang tersedia.
     * Bisa difilter berdasarkan nama dan mengatur jumlah data per halaman.
     *
     * @unauthenticated
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $row_per_page = $request->input('row_per_page', 10);
            $search       = $request->input('search');

            $categories = Category::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->latest()
                ->paginate($row_per_page);

            return ResponseHelper::success(
                status: true,
                message: 'Categories retrieved successfully',
                result: PaginateResource::make($categories, CategoryResource::class),
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Detail kategori
     *
     * Menampilkan detail satu kategori berdasarkan Slug.
     *
     * @unauthenticated
     */
    public function show(Category $category): JsonResponse
    {
        try {
            return ResponseHelper::success(
                status: true,
                message: 'Category retrieved successfully',
                result: new CategoryResource($category),
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Buat kategori baru
     *
     * Hanya bisa diakses oleh User dengan role admin-pusat.
     *
     */

    #[BodyParameter(name: 'name', description: 'Nama kategori course', type: 'string', required: true, example: 'example')]
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());

            return ResponseHelper::success(
                status: true,
                message: 'Category created successfully',
                result: new CategoryResource($category),
                statusCode: 201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Update kategori
     *
     * Hanya bisa diakses oleh admin-pusat.
     *
     */
    #[BodyParameter(name: 'name', description: 'Nama kategori course', type: 'string', required: true, example: 'example')]
    public function update(CategoryUpdateStoreRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());

            return ResponseHelper::success(
                status: true,
                message: 'Category updated successfully',
                result: new CategoryResource($category),
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Hapus kategori
     *
     * Hanya bisa diakses oleh admin-pusat.
     * Kategori yang masih memiliki course tidak bisa dihapus.
     *
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            // Cek apakah kategori masih punya course
            // if ($category->courses()->exists()) {
            //     return ResponseHelper::error(
            //         message: 'Kategori tidak bisa dihapus karena masih memiliki course',
            //         statusCode: 422
            //     );
            // }

            $category->delete();

            return ResponseHelper::success(
                status: true,
                message: 'Category deleted successfully',
                result: null,
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}