<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('post')) {
            $employee = \App\Models\Employee::find($this->input('employee_id'));

            if (!$employee) {
                return false;
            }

            return $this->user()->can('create', [\App\Models\EmployeeEducation::class, $employee]);
        }

        return $this->user()->can('update', $this->route('education'));
    }

    public function rules(): array
    {
        // 'required' on create, 'sometimes' on update
        $rule = $this->isMethod('post') ? 'required' : 'sometimes';

        $rules = [
            'institution' => [$rule, 'string', 'max:255'],
            'degree' => [$rule, 'string', 'max:150'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'year_of_passing' => [$rule, 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'score_type' => [$rule, 'in:percentage,cgpa'],
            'score_value' => [$rule, 'numeric', 'min:0'],
        ];

        if ($this->isMethod('post')) {
            $rules['employee_id'] = ['required', 'exists:employees,id'];
        }

        return $rules;
    }

    /**
     * Extra rule: score_value must respect the max for its score_type.
     * percentage -> 0 to 100
     * cgpa -> 0 to 10
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // On update, fall back to the existing record's score_type if not sent in this request
            $existing = $this->route('education');
            $type = $this->input('score_type', $existing?->score_type);
            $value = $this->input('score_value');

            if ($value === null) {
                return;
            }

            if ($type === 'percentage' && $value > 100) {
                $validator->errors()->add('score_value', 'Percentage cannot exceed 100.');
            }

            if ($type === 'cgpa' && $value > 10) {
                $validator->errors()->add('score_value', 'CGPA cannot exceed 10.');
            }
        });
    }
}