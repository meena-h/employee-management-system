<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('post')) {
            return $this->user()->can('create', \App\Models\Employee::class);
        }

        return $this->user()->can('update', $this->route('employee'));
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');
        $rule = $isCreate ? 'required' : 'sometimes';
        $employeeId = $this->route('employee');

        $rules = [
            'first_name' => [$rule, 'string', 'max:100'],
            'last_name' => [$rule, 'string', 'max:100'],
            'date_of_birth' => [$rule, 'date', 'before:today'],
            'gender' => [$rule, 'in:male,female,other'],
            'marital_status' => [$rule, 'in:single,married,divorced,widowed'],
            'personal_email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('employees', 'personal_email')->ignore($employeeId),
            ],
            'phone_number' => [
                $rule, 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/',
                Rule::unique('employees', 'phone_number')->ignore($employeeId),
            ],
            'alternate_phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
            'current_address' => [$rule, 'string'],
            'permanent_address' => [$rule, 'string'],
            'blood_group' => ['nullable', 'string', 'max:5'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
        ];

        if ($isCreate) {
            $rules['user_id'] = ['nullable', 'exists:users,id', 'unique:employees,user_id'];
        }

        if ($isCreate || $this->user()->role === 'admin') {
            $rules['department'] = [$isCreate ? 'nullable' : 'sometimes', 'nullable', 'string', 'max:100'];
            $rules['designation'] = [$rule, 'string', 'max:100'];
            $rules['date_of_joining'] = [$rule, 'date'];
            $rules['employment_status'] = [$isCreate ? 'required' : 'sometimes', 'in:active,on_leave,resigned,terminated'];
        }

        if ($isCreate) {
            $rules['family_members'] = ['nullable', 'array'];
            $rules['family_members.*.name'] = ['required_with:family_members', 'string', 'max:150'];
            $rules['family_members.*.relationship'] = ['required_with:family_members', 'in:spouse,father,mother,child,sibling,other'];
            $rules['family_members.*.date_of_birth'] = ['nullable', 'date', 'before:today'];
            $rules['family_members.*.occupation'] = ['nullable', 'string', 'max:150'];
            $rules['family_members.*.contact_number'] = ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'];

            $rules['educations'] = ['nullable', 'array'];
            $rules['educations.*.institution'] = ['required_with:educations', 'string', 'max:255'];
            $rules['educations.*.degree'] = ['required_with:educations', 'string', 'max:150'];
            $rules['educations.*.specialization'] = ['nullable', 'string', 'max:150'];
            $rules['educations.*.year_of_passing'] = ['required_with:educations', 'integer', 'min:1950', 'max:' . (date('Y') + 1)];
            $rules['educations.*.score_type'] = ['required_with:educations', 'in:percentage,cgpa'];
            $rules['educations.*.score_value'] = ['required_with:educations', 'numeric', 'min:0'];
        }

        return $rules;
    }
}