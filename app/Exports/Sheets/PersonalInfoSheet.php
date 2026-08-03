<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PersonalInfoSheet implements FromCollection, WithHeadings, WithTitle
{
    public function __construct(protected Collection $employees) {}

    public function collection()
    {
        return $this->employees->map(function ($employee) {
            return [
                $employee->employee_code,
                $employee->first_name,
                $employee->last_name,
                $employee->date_of_birth,
                $employee->gender,
                $employee->phone_number,
                $employee->personal_email,
                $employee->department,
                $employee->designation,
                $employee->date_of_joining,
                $employee->employment_status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee Code', 'First Name', 'Last Name', 'Date of Birth', 'Gender',
            'Phone', 'Email', 'Department', 'Designation', 'Date of Joining', 'Status',
        ];
    }

    public function title(): string
    {
        return 'Personal Info';
    }
}