<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'institution' => $this->institution,
            'degree' => $this->degree,
            'specialization' => $this->specialization,
            'year_of_passing' => $this->year_of_passing,
            'score_type' => $this->score_type,
            'score_value' => $this->score_value,
        ];
    }
}