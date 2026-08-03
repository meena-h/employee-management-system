<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeCodeGenerator
{
    /**
     * Generates a unique employee code in the format EMP-{year}-{sequence}.
     * Example: EMP-2026-0001, EMP-2026-0002, ...
     *
     * Wrapped in a DB transaction + row lock to prevent two simultaneous
     * employee creations from generating the same code (a race condition).
     */
    public function generate(): string
    {
        return DB::transaction(function () {
            $year = now()->year;

            $count = Employee::withTrashed()
                ->whereYear('created_at', $year)
                ->lockForUpdate()
                ->count();

            $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            return "EMP-{$year}-{$sequence}";
        });
    }
}