<?php

namespace App\Exports\Sheets;

use App\Models\EmployeeFamilyMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FamilyMembersSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        $members = EmployeeFamilyMember::with('employee')->get();

        $rows = collect();

        foreach ($members as $member) {
            $rows->push([
                $member->employee->employee_code,
                $member->name,
                $member->relationship,
                $member->date_of_birth,
                $member->occupation,
                $member->contact_number,
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Employee Code', 'Name', 'Relationship', 'Date of Birth', 'Occupation', 'Contact Number'];
    }

    public function title(): string
    {
        return 'Family Members';
    }
}