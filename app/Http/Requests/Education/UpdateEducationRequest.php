<?php

namespace App\Http\Requests\Education;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('education'));
    }

    public function rules(): array
    {
        return [
            'institution' => ['sometimes', 'string', 'max:255'],
            'degree' => ['sometimes', 'string', 'max:150'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'year_of_passing' => ['sometimes', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'score_type' => ['sometimes', 'in:percentage,cgpa'],
            'score_value' => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $type = $this->input('score_type', $this->route('education')?->score_type);
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