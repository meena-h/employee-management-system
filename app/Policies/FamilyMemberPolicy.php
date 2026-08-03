<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\EmployeeFamilyMember;
use App\Models\User;

class FamilyMemberPolicy
{
    public function viewAny(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    public function view(User $user, EmployeeFamilyMember $familyMember): bool
    {
        return $user->role === 'admin' || $familyMember->employee->user_id === $user->id;
    }

    public function create(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    public function update(User $user, EmployeeFamilyMember $familyMember): bool
    {
        return $user->role === 'admin' || $familyMember->employee->user_id === $user->id;
    }

    public function delete(User $user, EmployeeFamilyMember $familyMember): bool
    {
        return $user->role === 'admin' || $familyMember->employee->user_id === $user->id;
    }
}