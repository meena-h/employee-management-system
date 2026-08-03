<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    /**
     * Only admin can list ALL employees.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Admin can view any employee.
     * Staff can only view their OWN linked employee record.
     */
    public function view(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    /**
     * Only admin can create new employee records.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Admin can update any employee.
     * Staff can update ONLY their own record (field-level restrictions
     * are handled separately in the Form Request, not here).
     */
    public function update(User $user, Employee $employee): bool
    {
        return $user->role === 'admin' || $employee->user_id === $user->id;
    }

    /**
     * Only admin can soft-delete an employee.
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $user->role === 'admin';
    }
}