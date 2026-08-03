<?php

namespace App\Http\Requests\Education;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [\App\Models\EmployeeEducation::class, $this->route('employee')]);
    }

    public function rules(): array
    {
        return [
            'institution' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:150'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'year_of_passing' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'score_type' => ['required', 'in:percentage,cgpa'],
            'score_value' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Extra rule: score_value must respect the max for its score_type.
     * percentage -> 0 to 100
     * cgpa -> 0 to 10
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $type = $this->input('score_type');
            $value = $this->input('score_value');

            if ($type === 'percentage' && $value > 100) {
                $validator->errors()->add('score_value', 'Percentage cannot exceed 100.');
            }

            if ($type === 'cgpa' && $value > 10) {
                $validator->errors()->add('score_value', 'CGPA cannot exceed 10.');
            }
        });
    }
}