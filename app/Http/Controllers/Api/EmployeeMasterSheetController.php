<?php

namespace App\Http\Controllers\Api;

use App\Exports\EmployeeMasterSheet;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeMasterSheetController extends Controller
{
    public function export()
    {
        $this->authorize('viewAny', Employee::class);

        return Excel::download(new EmployeeMasterSheet, 'employees.xlsx');
    }
}