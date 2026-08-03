<?php

namespace App\Http\Requests\FamilyMember;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [\App\Models\EmployeeFamilyMember::class, $this->route('employee')]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'relationship' => ['required', 'in:spouse,father,mother,child,sibling,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'occupation' => ['nullable', 'string', 'max:150'],
            'contact_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
        ];
    }
}