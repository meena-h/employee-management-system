<?php

namespace App\Http\Controllers\Api;

use App\Exports\EmployeesExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeExportController extends Controller
{
    public function export()
    {
        $this->authorize('viewAny', Employee::class);

        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }
}