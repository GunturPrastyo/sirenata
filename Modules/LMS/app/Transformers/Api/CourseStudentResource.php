<?php

namespace Modules\LMS\Transformers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'email'                => $this->email,
            'status'               => $this->pivot->status,
            'progress'             => $this->pivot->progress,
            'completed_at'         => $this->pivot->completed_at,
            'certificate_code'     => $this->pivot->certificate_code,
            'certificate_issued_at'=> $this->pivot->certificate_issued_at,
            'enrolled_at'          => $this->pivot->created_at,
        ];
    }
}
