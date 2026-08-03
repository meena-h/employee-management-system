<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\BiodataPdfService;

class EmployeeReportController extends Controller
{
    public function __construct(protected BiodataPdfService $pdfService) {}

    public function download(Employee $employee)
    {
        $this->authorize('view', $employee);

        $content = $this->pdfService->generate($employee);

        return response($content, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$employee->employee_code}-biodata.pdf");
    }
}