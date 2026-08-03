<?php

namespace App\Http\Requests\Experience;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [\App\Models\EmployeeExperience::class, $this->route('employee')]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'responsibilities' => ['nullable', 'string', 'max:2000'],
        ];
    }
}