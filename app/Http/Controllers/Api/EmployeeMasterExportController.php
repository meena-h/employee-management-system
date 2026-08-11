<?php

namespace App\Http\Controllers\Api;

use App\Exports\EmployeeMasterExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeMasterExportController extends Controller
{
    public function export()
    {
        $this->authorize('viewAny', Employee::class);

        try {
            
            return Excel::download(new EmployeeMasterExport, 'employees.xlsx');

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to export employees.',
            ], 500);
        }    
    }
}