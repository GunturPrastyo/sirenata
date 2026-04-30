<?php

namespace Modules\LMS\Services;

use App\Models\User;
use Illuminate\Support\Collection;
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
        $this->baseUrl = config('Lms.api_url', env('LMS_API_URL', 'https://e-learning.test/api/v1'));
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
        $course = Auth::user()
            ->enrolledCourses()
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
        return Auth::user()
            ->enrolledCourses()
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
            $response = Http::withToken($token)
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
            ];
        }
    }

    public function getCourseDetailSlug(string $token, string $slug): array
    {
        try {
            $response = Http::withToken($token)
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
}
