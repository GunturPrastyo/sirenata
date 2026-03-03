<?php

namespace Modules\LMS\Services;

use App\Models\User;
use Modules\User\Enums\InstitutionType;

class CourseService
{
    private const DEFAULT_SORT = 'desc';
    private const DEFAULT_LIMIT = 10;


    public function queryCourseByProvince(string $provinceCode, string $search = '', int $limit = self::DEFAULT_LIMIT, string $sort = self::DEFAULT_SORT)
    {
        return User::query()
            ->inProvince($provinceCode)
            ->provinceInstitution()
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

    public function paginatedCourseByProvince(string $provinceCode, string $search = '', int $limit = self::DEFAULT_LIMIT, string $sort = self::DEFAULT_SORT)
    {
        return $this->queryCourseByProvince(
            provinceCode: $provinceCode,
            search: $search,
            sort: $sort,
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
}