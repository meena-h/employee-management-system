<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\EmployeeExperience;
use App\Models\User;

class ExperiencePolicy
{
    public function viewAny(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    public function view(User $user, EmployeeExperience $experience): bool
    {
        return $user->role === 'admin' || $experience->employee->user_id === $user->id;
    }

    public function create(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    public function update(User $user, EmployeeExperience $experience): bool
    {
        return $user->role === 'admin' || $experience->employee->user_id === $user->id;
    }

    public function delete(User $user, EmployeeExperience $experience): bool
    {
        return $user->role === 'admin' || $experience->employee->user_id === $user->id;
    }
}