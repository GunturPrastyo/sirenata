<?php

namespace Modules\LMS\Transformers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'position'    => $this->position,
            'contents'    => SectionContentResource::collection($this->whenLoaded('contents')),
            'contents_count' => $this->whenCounted('contents'),
        ];
    }
}
