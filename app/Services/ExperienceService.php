<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeExperience;
use Carbon\Carbon;

class ExperienceService
{
    public function create(Employee $employee, array $data): EmployeeExperience
    {
        $data['total_experience_months'] = $this->calculateMonths(
            $data['start_date'],
            $data['end_date'] ?? null
        );

        return $employee->experiences()->create($data);
    }

    public function update(EmployeeExperience $experience, array $data): EmployeeExperience
    {
        $startDate = $data['start_date'] ?? $experience->start_date;
        $endDate = array_key_exists('end_date', $data) ? $data['end_date'] : $experience->end_date;

        $data['total_experience_months'] = $this->calculateMonths($startDate, $endDate);

        $experience->update($data);

        return $experience->fresh();
    }

    private function calculateMonths(string $start, ?string $end): int
    {
        $startDate = Carbon::parse($start);
        $endDate = $end ? Carbon::parse($end) : Carbon::now();

        return $startDate->diffInMonths($endDate);
    }
}