<?php

namespace App\Http\Requests\Experience;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('experience'));
    }

    public function rules(): array
    {
        return [
            'company_name' => ['sometimes', 'string', 'max:255'],
            'designation' => ['sometimes', 'string', 'max:150'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'responsibilities' => ['nullable', 'string', 'max:2000'],
        ];
    }
}