<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FamilyMembersSheet implements FromCollection, WithHeadings, WithTitle
{
    public function __construct(protected Collection $employees) {}

    public function collection()
    {
        $rows = collect();

        foreach ($this->employees as $employee) {
            foreach ($employee->familyMembers as $member) {
                $rows->push([
                    $employee->employee_code,
                    $member->name,
                    $member->relationship,
                    $member->date_of_birth,
                    $member->occupation,
                    $member->contact_number,
                ]);
            }
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