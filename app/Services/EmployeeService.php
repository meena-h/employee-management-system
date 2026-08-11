<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;

class EmployeeService
{
    public function __construct(
        protected EmployeeCodeGenerator $codeGenerator
    ) {}

    public function create(array $data, User $creator): Employee
    {
            $familyMembers = $data['family_members'] ?? [];
            $educations = $data['educations'] ?? [];
            $experiences = $data['experiences'] ?? [];
            unset(
                $data['family_members'],
                $data['educations'], 
                $data['experiences'
            ]);

            $data['employee_code'] = $this->codeGenerator->generate();
            $data['created_by'] = $creator->id;

            $employee = Employee::create($data);

            foreach ($familyMembers as $member) {
                $employee->familyMembers()->create($member);
            }

            foreach ($educations as $education) {
                $employee->educations()->create($education);
            }

            foreach ($experiences as $experience) {
                $employee->experiences()->create($experience);
            }

            return $employee;
    }

    public function update(Employee $employee, array $data, User $updater): Employee
    {
        $data['updated_by'] = $updater->id;
        $employee->update($data);

        return $employee->fresh();
    }

    public function delete(Employee $employee): void
    {
        $employee->familyMembers()->delete();
        $employee->educations()->delete();
        $employee->experiences()->delete();
        $employee->delete();
    }
}