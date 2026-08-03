<?php

namespace App\Observers;

use App\Models\Employee;

class EmployeeObserver
{
    /**
     * Fires right before an Employee is soft-deleted.
     * Cascades the soft delete to all related child records.
     */
    public function deleting(Employee $employee): void
    {
        $employee->familyMembers()->delete();
        $employee->educations()->delete();
        $employee->experiences()->delete();
    }

    /**
     * Fires right after an Employee is restored (if you add a restore
     * endpoint later). Cascades the restore to related child records.
     */
    public function restored(Employee $employee): void
    {
        $employee->familyMembers()->onlyTrashed()->restore();
        $employee->educations()->onlyTrashed()->restore();
        $employee->experiences()->onlyTrashed()->restore();
    }
}