<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('post')) {
            $employee = \App\Models\Employee::find($this->input('employee_id'));

            if (!$employee) {
                return false;
            }

            return $this->user()->can('create', [\App\Models\EmployeeExperience::class, $employee]);
        }

        return $this->user()->can('update', $this->route('experience'));
    }

    public function rules(): array
    {
        $rule = $this->isMethod('post') ? 'required' : 'sometimes';

        $rules = [
            'company_name' => [$rule, 'string', 'max:255'],
            'designation' => [$rule, 'string', 'max:150'],
            'start_date' => [$rule, 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'responsibilities' => ['nullable', 'string', 'max:2000'],
        ];

        if ($this->isMethod('post')) {
            $rules['employee_id'] = ['required', 'exists:employees,id'];
        }

        return $rules;
    }
}