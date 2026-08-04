<?php

namespace App\Http\Requests\User;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');
        $rule = $isCreate ? 'required' : 'sometimes';
        $userId = $this->route('user'); // null on create, the model on update

        $rules = [
            'name' => [$rule, 'string', 'max:255'],
            'email' => [$rule, 'email', Rule::unique('users', 'email')->ignore($userId)],
            'role' => [$rule, 'in:admin,staff'],
            'employee_id' => ['nullable', 'exists:employees,id'],
        ];

        if ($isCreate) {
            $rules['password'] = ['required', 'string', 'min:8'];
        } else {
            $rules['is_active'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }

    /**
     * On create: employee_id must not already be linked to a different user.
     * On update: same check, but ignore if it's already linked to THIS user.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $employeeId = $this->input('employee_id');

            if (!$employeeId) {
                return;
            }

            $employee = Employee::find($employeeId);
            $currentUserId = $this->route('user')?->id;

            if ($employee && $employee->user_id !== null && $employee->user_id !== $currentUserId) {
                $validator->errors()->add(
                    'employee_id',
                    'This employee is already linked to another user account.'
                );
            }
        });
    }
}