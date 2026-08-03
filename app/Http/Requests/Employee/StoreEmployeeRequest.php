<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Employee::class);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id', 'unique:employees,user_id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'marital_status' => ['required', 'in:single,married,divorced,widowed'],
            'personal_email' => ['nullable', 'email', 'max:255', 'unique:employees,personal_email'],
            'phone_number' => ['required', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/', 'unique:employees,phone_number'],
            'alternate_phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
            'current_address' => ['required', 'string'],
            'permanent_address' => ['required', 'string'],
            'department' => ['nullable', 'string', 'max:100'],
            'designation' => ['required', 'string', 'max:100'],
            'date_of_joining' => ['required', 'date'],
            'employment_status' => ['required', 'in:active,on_leave,resigned,terminated'],
            'blood_group' => ['nullable', 'string', 'max:5'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
        ];
    }
}