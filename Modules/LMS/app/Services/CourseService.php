<?php

namespace Modules\LMS\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\LMS\Models\Course;
use Modules\User\Enums\InstitutionType;

class CourseService
{
    private const DEFAULT_SORT = 'desc';
    private const DEFAULT_LIMIT = 10;


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
            ->select(
                'users.name as user_name',
                'courses.name as course_name',
                'user_profiles.instansi',
                'course_student.status',
                'course_student.progress'
            );
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
            ->select(
                'users.name as user_name',
                'courses.name as course_name',
                'user_profiles.instansi',
                'course_student.status',
                'course_student.progress'
            );
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
}