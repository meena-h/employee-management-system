<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function __construct(
        protected EmployeeCodeGenerator $codeGenerator
    ) {}

    public function create(array $data, User $creator): Employee
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['employee_code'] = $this->codeGenerator->generate();
            $data['created_by'] = $creator->id;

            return Employee::create($data);
        });
    }

    public function update(Employee $employee, array $data, User $updater): Employee
    {
        $data['updated_by'] = $updater->id;
        $employee->update($data);

        return $employee->fresh();
    }

    public function delete(Employee $employee): void
    {
        $employee->delete(); // cascades to children via EmployeeObserver (Step 12)
    }
}