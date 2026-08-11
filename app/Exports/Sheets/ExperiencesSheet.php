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
        return $this->employees
        ->pluck('experiences')
        ->flatten()
        ->map(function ($experience) {
            return [
                $experience->employee->employee_code,
                $experience->company_name,
                $experience->designation,
                $experience->start_date,
                $experience->end_date ?? 'Present',
                $experience->total_experience_months,
            ];
        });

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