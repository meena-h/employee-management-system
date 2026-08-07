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
            $familyMembers = $data['family_members'] ?? [];
            $educations = $data['educations'] ?? [];
            unset($data['family_members'], $data['educations']);

            $data['employee_code'] = $this->codeGenerator->generate();
            $data['created_by'] = $creator->id;

            $employee = Employee::create($data);

            foreach ($familyMembers as $member) {
                $employee->familyMembers()->create($member);
            }

            foreach ($educations as $education) {
                $employee->educations()->create($education);
            }

            return $employee;
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
        DB::transaction(function () use ($employee) {
        $employee->familyMembers()->delete();
        $employee->educations()->delete();
        $employee->experiences()->delete();
        $employee->delete();
        });
    }
}