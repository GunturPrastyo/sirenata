<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScopeAreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'province_code' => $this->province_code,
            'province_name' => $this->whenLoaded(
                'province',
                fn () => $this->province?->name
            ),

            'regency_code' => $this->regency_code,
            'regency_name' => $this->whenLoaded(
                'regency',
                fn () => $this->regency?->name
            ),
        ];
    }
}
