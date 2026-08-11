<?php

namespace App\Exports;

use App\Exports\Sheets\EducationsSheet;
use App\Exports\Sheets\ExperiencesSheet;
use App\Exports\Sheets\FamilyMembersSheet;
use App\Exports\Sheets\PersonalInfoSheet;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeMasterExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $employees = Employee::with(['familyMembers.employee', 'educations.employee', 'experiences.employee'])->get();

        return [
            new PersonalInfoSheet($employees),
            new FamilyMembersSheet($employees),
            new EducationsSheet($employees),
            new ExperiencesSheet($employees),
        ];
    }
}