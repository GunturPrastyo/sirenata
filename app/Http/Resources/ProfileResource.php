<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'institution_type' => $this->institution_type,
            'instansi' => $this->instansi,
            'unit_kerja' => $this->unit_kerja,
            'nik' => $this->nik,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'gender' => $this->gender,
        ];
    }
}
