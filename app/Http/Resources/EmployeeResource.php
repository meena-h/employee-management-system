<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_code' => $this->employee_code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'personal_email' => $this->personal_email,
            'phone_number' => $this->phone_number,
            'alternate_phone_number' => $this->alternate_phone_number,
            'current_address' => $this->current_address,
            'permanent_address' => $this->permanent_address,
            'department' => $this->department,
            'designation' => $this->designation,
            'date_of_joining' => $this->date_of_joining,
            'employment_status' => $this->employment_status,
            'blood_group' => $this->blood_group,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_number' => $this->emergency_contact_number,
            'family_members' => FamilyMemberResource::collection($this->whenLoaded('familyMembers')),
            'educations' => EducationResource::collection($this->whenLoaded('educations')),
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}