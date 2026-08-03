<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExperiencesSheet implements FromCollection, WithHeadings, WithTitle
{
    public function __construct(protected Collection $employees) {}

    public function collection()
    {
        $rows = collect();

        foreach ($this->employees as $employee) {
            foreach ($employee->experiences as $exp) {
                $rows->push([
                    $employee->employee_code,
                    $exp->company_name,
                    $exp->designation,
                    $exp->start_date,
                    $exp->end_date ?? 'Present',
                    $exp->total_experience_months,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Employee Code', 'Company', 'Designation', 'Start Date', 'End Date', 'Total Months'];
    }

    public function title(): string
    {
        return 'Experience';
    }
}