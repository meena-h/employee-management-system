<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('employee'));
    }

    public function rules(): array
    {
        $rules = [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'date_of_birth' => ['sometimes', 'date', 'before:today'],
            'gender' => ['sometimes', 'in:male,female,other'],
            'marital_status' => ['sometimes', 'in:single,married,divorced,widowed'],
            'personal_email' => ['nullable', 'email', 'max:255', Rule::unique('employees', 'personal_email')->ignore($this->route('employee'))],
            'phone_number' => ['sometimes', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/', Rule::unique('employees', 'phone_number')->ignore($this->route('employee'))],
            'current_address' => ['sometimes', 'string'],
            'permanent_address' => ['sometimes', 'string'],
            'blood_group' => ['nullable', 'string', 'max:5'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
        ];

        // Only admin may change these sensitive/administrative fields
        if ($this->user()->role === 'admin') {
            $rules['department'] = ['sometimes', 'nullable', 'string', 'max:100'];
            $rules['designation'] = ['sometimes', 'string', 'max:100'];
            $rules['date_of_joining'] = ['sometimes', 'date'];
            $rules['employment_status'] = ['sometimes', 'in:active,on_leave,resigned,terminated'];
        }

        return $rules;
    }
}