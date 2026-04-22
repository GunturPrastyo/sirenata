<?php

namespace Modules\LMS\Transformers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyCourseResource extends JsonResource
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
            'thumbnail_url' => $this->thumbnail_url,
            'category'      => $this->category?->name,
            'status'        => $this->pivot->status,
            'progress'      => $this->pivot->progress,
            'enrolled_at'   => $this->pivot->created_at,
            'benefits'      => CourseBenefitResource::collection($this->whenLoaded('benefits')),
            'testimonis'    => CourseTestimoniResource::collection($this->whenLoaded('testimonis')),
            'course_sections'  => CourseSectionResource::collection($this->whenLoaded('sections')),
        ];

    }
}
