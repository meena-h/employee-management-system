<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EducationsSheet implements FromCollection, WithHeadings, WithTitle
{
    public function __construct(protected Collection $employees) {}
    public function collection()
    {

        return $this->employees
    ->pluck('educations')
    ->flatten()
    ->map(function ($education) {
        return [
            $education->employee->employee_code,
            $education->institution,
            $education->degree,
            $education->specialization,
            $education->year_of_passing,
            $education->score_type,
            $education->score_value,
        ];
    });

    }

    public function headings(): array
    {
        return ['Employee Code', 'Institution', 'Degree', 'Specialization', 'Year of Passing', 'Score Type', 'Score Value'];
    }

    public function title(): string
    {
        return 'Education';
    }
}