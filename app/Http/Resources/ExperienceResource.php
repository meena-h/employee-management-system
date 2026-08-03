<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'designation' => $this->designation,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'responsibilities' => $this->responsibilities,
            'total_experience_months' => $this->total_experience_months,
        ];
    }
}