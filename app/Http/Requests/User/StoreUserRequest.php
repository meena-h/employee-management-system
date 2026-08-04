<?php

namespace App\Http\Requests\User;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,staff'],
            'employee_id' => ['nullable', 'exists:employees,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $employeeId = $this->input('employee_id');

            if (!$employeeId) {
                return;
            }

            $employee = Employee::find($employeeId);

            if ($employee && $employee->user_id !== null) {
                $validator->errors()->add(
                    'employee_id',
                    'This employee is already linked to another user account.'
                );
            }
        });
    }
}