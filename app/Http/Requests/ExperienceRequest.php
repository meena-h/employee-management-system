<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('post')) {
            return $this->user()->can('create', [\App\Models\EmployeeExperience::class, $this->route('employee')]);
        }

        return $this->user()->can('update', $this->route('experience'));
    }

    public function rules(): array
    {
        $rule = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'company_name' => [$rule, 'string', 'max:255'],
            'designation' => [$rule, 'string', 'max:150'],
            'start_date' => [$rule, 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'responsibilities' => ['nullable', 'string', 'max:2000'],
        ];
    }
}