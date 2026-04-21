<?php

namespace Modules\LMS\Services\Api;

use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseBenefits;

class CourseBenefitsService
{
    public function queryBenefits(string $slug)
    {
        $course   = Course::where('slug', $slug)->firstOrFail();
        $benefits = $course->benefits()->get();
        return $benefits;
    }

    public function CourseBenefitStore(array $data, string $slug) {
        $course   = Course::where('slug', $slug)->firstOrFail();
        $benefit = $course->benefits()->create($data);
        return $benefit;
    }

    public function CourseBenefitUpdate(array $data, CourseBenefits $benefitId)
    {
        $benefitId->update($data);
        return $benefitId;
    }

    public function CourseBenefitDelete(CourseBenefits $benefitId) {
        return $benefitId->delete();
    }
}
