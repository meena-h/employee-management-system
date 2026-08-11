<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('post')) {
            $employee = \App\Models\Employee::find($this->input('employee_id'));

            if (!$employee) {
                return false;
            }

            return $this->user()->can('create', [\App\Models\EmployeeFamilyMember::class, $employee]);
        }

        return $this->user()->can('update', $this->route('familyMember'));
    }

    public function rules(): array
    {
        $rule = $this->isMethod('post') ? 'required' : 'sometimes';

        $rules = [
            'name' => [$rule, 'string', 'max:150'],
            'relationship' => [$rule, 'in:spouse,father,mother,child,sibling,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'occupation' => ['nullable', 'string', 'max:150'],
            'contact_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
        ];

        if ($this->isMethod('post')) {
            $rules['employee_id'] = ['required', 'exists:employees,id'];
        }

        return $rules;
    }
}