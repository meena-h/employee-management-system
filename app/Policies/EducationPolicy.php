<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\EmployeeEducation;
use App\Models\User;

class EducationPolicy
{
    public function viewAny(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    public function view(User $user, EmployeeEducation $education): bool
    {
        return $user->role === 'admin' || $education->employee->user_id === $user->id;
    }

    public function create(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    public function update(User $user, EmployeeEducation $education): bool
    {
        return $user->role === 'admin' || $education->employee->user_id === $user->id;
    }

    public function delete(User $user, EmployeeEducation $education): bool
    {
        return $user->role === 'admin' || $education->employee->user_id === $user->id;
    }
}