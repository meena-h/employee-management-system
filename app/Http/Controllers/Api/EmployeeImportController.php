<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeImportController extends Controller
{
    public function import(Request $request)
    {
        $this->authorize('create', Employee::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv', 'max:5120'],
        ]);

        try {

            $import = new EmployeesImport($request->user()->id);
            Excel::import($import, $request->file('file'));

            return response()->json([
                'message' => 'Import completed.',
                'imported_count' => $import->getImportedCount(),
                'failure_count' => count($import->failures()),
                'failures' => $import->failures()->map(function ($failure) {
                    return [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                    ];
                })->values(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Import failed.',
            ], 500);
        }
    }
}