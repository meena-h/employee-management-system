<?php

namespace App\Http\Requests\FamilyMember;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('familyMember'));
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'relationship' => ['sometimes', 'in:spouse,father,mother,child,sibling,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'occupation' => ['nullable', 'string', 'max:150'],
            'contact_number' => ['nullable', 'string', 'regex:/^\+?[0-9\-\s]{7,20}$/'],
        ];
    }
}