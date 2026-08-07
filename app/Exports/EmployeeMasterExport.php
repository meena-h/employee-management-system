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
        $employees = Employee::all();

        return [
            new PersonalInfoSheet($employees),
            new FamilyMembersSheet(),
            new EducationsSheet(),
            new ExperiencesSheet(),
        ];
    }
}