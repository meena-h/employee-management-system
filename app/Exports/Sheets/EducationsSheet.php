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
        $rows = collect();

        foreach ($this->employees as $employee) {
            foreach ($employee->educations as $edu) {
                $rows->push([
                    $employee->employee_code,
                    $edu->institution,
                    $edu->degree,
                    $edu->specialization,
                    $edu->year_of_passing,
                    $edu->score_type,
                    $edu->score_value,
                ]);
            }
        }

        return $rows;
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