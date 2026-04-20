<?php

namespace Modules\LMS\Transformers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'thumbnail'     => $this->thumbnail,
            'description'   => $this->description,
            'category'         => CategoryResource::make($this->whenLoaded('category')),
            'benefits_count'   => $this->whenCounted('benefits'),
            'students_count'   => $this->whenCounted('students'),
            'sections_count'   => $this->whenCounted('sections'),
            'benefits'         => CourseBenefitResource::collection($this->whenLoaded('benefits')),
            'testimonis'       => CourseTestimoniResource::collection($this->whenLoaded('testimonis')),
            'course_sections'  => CourseSectionResource::collection($this->whenLoaded('sections')),
            'created_at'       => $this->created_at->toDateTimeString(),
        ];
    }
}
