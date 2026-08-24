<?php

namespace Modules\LMS\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\LMS\Models\Course;
use Modules\User\Enums\InstitutionType;
use Illuminate\Support\Facades\Http;

class CourseService
{
    private const DEFAULT_SORT = 'desc';
    private const DEFAULT_LIMIT = 10;

    /**
     * Base URL API — nanti tinggal ganti ke URL KCLC di .env
     */
    private string $baseUrl;
    public function __construct()
    {
        $defaultUrl = request()->getSchemeAndHttpHost() . '/api/v1';
        $this->baseUrl = (string) config('Lms.api_url', config('lms.api_url', env('LMS_API_URL', $defaultUrl)));
    }

    public function getCoursesForFilter()
    {
        return Course::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
    public function queryUsersWithEnrollmentsByProvince(
        string $provinceCode,
        string $search = '',
        ?string $courseId = null,
    ) {
        return User::query()
            ->inProvince($provinceCode)
            ->provinceInstitution()
            ->whereHas('enrolledCourses') // wajib punya course
            ->when($courseId, function ($q) use ($courseId) {
                $q->whereHas('enrolledCourses', function ($sub) use ($courseId) {
                    $sub->where('courses.id', $courseId);
                });
            })
            ->when($search, fn($q) => $q->search($search))
            ->with([
                'profile',
                'scopeArea',
                'enrolledCourses' => function ($q) use ($courseId) {
                    if ($courseId) {
                        $q->where('courses.id', $courseId);
                    }
                }
            ]);
    }

    public function paginateUsersWithEnrollmentsByProvince(string $provinceCode, string $search = '', int $limit = self::DEFAULT_LIMIT, ?string $courseId = null)
    {
        return $this->queryUsersWithEnrollmentsByProvince(
            provinceCode: $provinceCode,
            search: $search,
            courseId: $courseId,
        )->paginate($limit)->withQueryString();
    }

    private function baseEnrollmentsByProvinceQuery(
        string $provinceCode,
        ?string $courseId = null,
        ?string $search = null,
    ) {
        return DB::table('course_student')
            ->join('users', 'users.id', '=', 'course_student.user_id')
            ->join('courses', 'courses.id', '=', 'course_student.course_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->join('user_scopes', 'user_scopes.user_id', '=', 'users.id')
            ->where('user_scopes.province_code', $provinceCode)
            ->where('user_profiles.institution_type', InstitutionType::PROVINSI)
            ->when($courseId, function ($q) use ($courseId) {
                $q->where('courses.id', $courseId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('users.name', 'like', "%{$search}%")
                        ->orWhere('user_profiles.instansi', 'like', "%{$search}%");
                });
            })
            ->select([
                'users.id',
                'users.name as user_name',
                'courses.name as course_name',
                'user_profiles.instansi',
                'user_profiles.full_name as user_full_name',
                'course_student.status',
                'course_student.progress'
            ]);
    }

    public function paginateCourseEnrollmentsByProvince(
        string $provinceCode,
        ?string $courseId = null,
        ?string $search = null,
        int $limit = 10
    ) {
        return $this->baseEnrollmentsByProvinceQuery(
            provinceCode: $provinceCode,
            courseId: $courseId,
            search: $search,
        )->paginate($limit)->withQueryString();
    }

    public function exportCourseEnrollmentsByProvince(
        string $provinceCode,
        ?string $courseId = null,
        ?string $search = null,
    ) {
        return $this->baseEnrollmentsByProvinceQuery(
            provinceCode: $provinceCode,
            courseId: $courseId,
            search: $search,
        )->orderBy('users.name');
    }

    public function queryCourseByRegency(string $regencyCode, string $search = '', int $limit = self::DEFAULT_LIMIT, string $sort = self::DEFAULT_SORT)
    {
        return User::query()
            ->inRegency($regencyCode)
            ->regencyInstitution()
            ->where(function ($q) use ($search) {
                $q->hasEnrolledCourses($search);
                if ($search) {
                    $q->orWhere(fn($q2) => $q2->hasEnrolledCourses()->search($search));
                }
            })
            ->with([
                'profile',
                'scopeArea',
                'enrolledCourses' => fn($q) => $search ? $q->where('name', 'like', "%{$search}%") : $q,
            ]);
    }

    public function paginatedCourseByRegency(string $regencyCode, string $search = '', int $limit = self::DEFAULT_LIMIT, string $sort = self::DEFAULT_SORT)
    {
        return $this->queryCourseByRegency(
            regencyCode: $regencyCode,
            search: $search,
            sort: $sort,
        )->paginate($limit)->withQueryString();
    }

    private function baseEnrollmentsByRegencyQuery(
        string $regencyCode,
        ?string $courseId = null,
        ?string $search = null,
    ) {
        return DB::table('course_student')
            ->join('users', 'users.id', '=', 'course_student.user_id')
            ->join('courses', 'courses.id', '=', 'course_student.course_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->join('user_scopes', 'user_scopes.user_id', '=', 'users.id')
            ->where('user_scopes.regency_code', $regencyCode)
            ->where('user_profiles.institution_type', InstitutionType::KAB_KOTA)
            ->when($courseId, function ($q) use ($courseId) {
                $q->where('courses.id', $courseId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('users.name', 'like', "%{$search}%")
                        ->orWhere('user_profiles.instansi', 'like', "%{$search}%");
                });
            })
            ->select([
                'users.name as user_name',
                'courses.name as course_name',
                'user_profiles.instansi',
                'course_student.status',
                'course_student.progress',
            ]);
    }

    public function paginateCourseEnrollmentsByRegency(
        string $regencyCode,
        ?string $courseId = null,
        ?string $search = null,
        int $limit = 10
    ) {
        return $this->baseEnrollmentsByRegencyQuery(
            regencyCode: $regencyCode,
            courseId: $courseId,
            search: $search,
        )->paginate($limit)->withQueryString();
    }

    public function exportCourseEnrollmentsByRegency(
        string $regencyCode,
        ?string $courseId = null,
        ?string $search = null,
    ) {
        return $this->baseEnrollmentsByRegencyQuery(
            regencyCode: $regencyCode,
            courseId: $courseId,
            search: $search,
        )->orderBy('users.name');
    }

    public function myCourseStats(): array
    {
       /** @var User $user */
        $user    = Auth::user();
        $courses = $user->enrolledCourses()->get();
        $startedCourses = $courses->where('pivot.progress', '>', 0);

        return [
            'total'        => $courses->count(),
            'aktif'        => $courses->where('pivot.status', 'in_progress')->count(),
            'terdaftar'    => $courses->where('pivot.status', 'enrolled')->count(),
            'selesai'      => $courses->where('pivot.status', 'completed')->count(),
            'sertifikat'   => $courses->whereNotNull('pivot.certificate_code')->count(),
            // 'avg_progress' => $$startedCourses->count() > 0
            //     ? (int) round($courses->where('pivot.progress', '>', 0)->avg('pivot.progress'))
            //     : 0,
            'avg_progress' => $startedCourses->count() > 0
                ? (int) round($startedCourses->avg('pivot.progress'))
                : 0,
        ];
    }

    /**
     * Ambil course terakhir yang diakses user
     * berdasarkan updated_at di pivot course_student
     */
    public function getLastAccessedCourse(): ?object
    {

       /** @var User $user */
        $user = Auth::user();

        $course = $user->enrolledCourses()
            ->wherePivotIn('status', ['enrolled', 'in_progress'])
            ->orderByPivot('updated_at', 'desc')
            ->first();

        if (! $course) return null;

        return (object) [
            'name'     => $course->name,
            'slug'     => $course->slug,
            'progress' => $course->pivot->progress,
            'status'   => $course->pivot->status,
        ];
    }


    /**
     * Ambil 3 course terbaru yang sedang diikuti user
     */
    public function getRecentCourses(int $limit = 3): \Illuminate\Support\Collection
    {

        /** @var User $user */
        $user = Auth::user();

        return $user->enrolledCourses()
            ->with('category')
            ->orderByPivot('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Ambil course yang diikuti user yang sedang login
     * Saat ini fetch dari API sendiri, nanti diganti API KCLC
     */
    public function myCourses(string $token, int $page = 1, int $perPage = 12, ?string $status = null): array
    {
        try {
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->timeout(10)
                ->get("{$this->baseUrl}/my-courses", [
                    'status'        => $status,
                    'page'          => $page,
                    'row_per_page'  => $perPage,
                ]);

            if ($response->failed()) {
                Log::error('Failed to fetch my courses', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data course',
                    'data'    => [],
                    'meta'    => [],
                    'links'   => [],
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Success',
                'data'    => $data['result']['data'] ?? [],
                'meta'    => $data['result']['meta'] ?? [],
                'links'   => $data['result']['links'] ?? [],
                'auth'    => $data['auth'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('CourseService::myCourses error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data'    => [],
                'meta'    => [],
                'links'   => [],
            ];
        }
    }

    public function getCourseDetailSlug(string $token, string $slug): array
    {
        try {
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->timeout(10)
                ->get("{$this->baseUrl}/courses/{$slug}/progress");

            if ($response->failed()) {
                Log::error('Failed to fetch course detail', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data course',
                    'data'    => [],
                    'meta'    => [],
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Success',
                'data'    => $data['result']['data'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('CourseService::getCourseDetailSlug error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data'    => [],
                'meta'    => [],
            ];
        }
    }


    /**
     * Get Data All Course Api Admin Pusat
     */
    public function allCourses(string $token, int $page = 1, int $perPage = 12, ?string $search = null, ?string $categoryId = null): array
    {
        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->get("{$this->baseUrl}/courses", [
                    'category_id'   => $categoryId,
                    'search'        => $search,
                    'page'          => $page,
                    'row_per_page'  => $perPage,
                ]);

            if ($response->failed()) {
                Log::error('Failed to fetch all courses', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data course',
                    'data'    => [],
                    'meta'    => [],
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Success',
                'data'    => $data['result']['data'] ?? [],
                'meta'    => $data['result']['meta'] ?? [],
                'links'   => $data['result']['links'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('CourseService::allCourses error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data'    => [],
                'meta'    => [],
            ];
        }
    }

    /**
     * Store/Post Data Course Api Admin Pusat
     */
    public function storeCourse(string $token, array $data, $thumbnailFile = null): array
    {
        try {
            // 1. Tambahkan acceptJson() di sini
            $client = Http::withToken($token)
                ->acceptJson()
                ->timeout(15);

            if ($thumbnailFile) {
                $client->attach(
                    'thumbnail',
                    file_get_contents($thumbnailFile->getRealPath()),
                    $thumbnailFile->getClientOriginalName()
                );
            }

            $response = $client->post("{$this->baseUrl}/courses", $data);

            // 2. Jika gagal (termasuk 422 Validation Error dari API)
            if ($response->failed()) {
                $errorData = $response->json(); // Ambil detail error JSON-nya

                Log::error('Failed to store course', [
                    'status' => $response->status(),
                    'body'   => $errorData ?? $response->body(),
                ]);

                // Ambil pesan error spesifik jika ada
                $errorMessage = $errorData['message'] ?? 'Terjadi kesalahan di server API';

                return [
                    'success' => false,
                    'message' => 'Gagal: ' . $errorMessage,
                    'data'    => [],
                ];
            }

            // 3. Jika berhasil
            $responseData = $response->json();

            // Perbaikan Log: gunakan array format, bukan digabung dengan string (.)
            Log::info('Response Data:', $responseData ?? []);

            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Course berhasil diperbarui',
                'data'    => $responseData['result'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('CourseService::updateCourse error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data',
                'data'    => [],
            ];
        }
    }
    /**
     * Update/PUT Data Course Api Admin Pusat
     */
    public function updateCourse(string $token, array $data, $thumbnailFile = null): array
    {
        try {
            $client = Http::withToken($token)
                ->acceptJson()
                ->timeout(15);

            if ($thumbnailFile) {
                $client->attach(
                    'thumbnail',
                    file_get_contents($thumbnailFile->getRealPath()),
                    $thumbnailFile->getClientOriginalName()
                );
            }

            $data['_method'] = 'PUT';
            $response = $client->post("{$this->baseUrl}/courses/{$data['slug']}", $data);

            if ($response->failed()) {
                $errorData = $response->json();

                Log::error('Failed to update course', [
                    'status' => $response->status(),
                    'body'   => $errorData ?? $response->body(),
                ]);

                $errorMessage = $errorData['message'] ?? 'Terjadi kesalahan di server API';

                return [
                    'success' => false,
                    'message' => 'Gagal: ' . $errorMessage,
                    'data'    => [],
                ];
            }

            $responseData = $response->json();
            Log::info('Response Data:', $responseData ?? []);

            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Course berhasil diperbarui',
                'data'    => $responseData['result'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('CourseService::updateCourse error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data',
                'data'    => [],
            ];
        }
    }

    /**
     * Delete Data Course Api Admin Pusat
     */
    public function deleteCourse(string $token, string $slug): array
    {
        try {
            $client = Http::withToken($token)
                ->acceptJson()
                ->timeout(15);

            $response = $client->delete("{$this->baseUrl}/courses/{$slug}");

            if ($response->failed()) {
                $errorData = $response->json();

                Log::error('Failed to delete course', [
                    'status' => $response->status(),
                    'body'   => $errorData ?? $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal menghapus: ' . ($errorData['message'] ?? 'Terjadi kesalahan di server API'),
                ];
            }

            $responseData = $response->json();

            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Course berhasil dihapus',
            ];
        } catch (\Exception $e) {
            Log::error('CourseService::deleteCourse error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menghapus data',
            ];
        }
    }


    /**
     * Get Detail Course Api Admin Pusat
     */
    public function getCourseBySlug(string $token, string $slug): array
    {
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(15)
                ->get("{$this->baseUrl}/courses/{$slug}");

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => 'Course tidak ditemukan',
                    'data'    => null,
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'message' => 'Success',
                'data'    => $data['result'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data'    => null,
            ];
        }
    }
}
