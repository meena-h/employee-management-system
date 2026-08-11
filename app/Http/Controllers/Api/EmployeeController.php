<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Employee::class);

        $employees = Employee::query()->paginate(15);

        return EmployeeResource::collection($employees);
    }

    public function store(EmployeeRequest $request)
    {
        try {
            $employee = DB::transaction(function () use ($request) {
                return $this->employeeService->create(
                    $request->validated(),
                    $request->user()
                );
            });

            return (new EmployeeResource($employee->load(['familyMembers', 'educations', 'experiences'])))
                ->response()
                ->setStatusCode(201);
        } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Failed to create employee.',
            'error' => $e->getMessage(),
        ], 500);
    }    
    }

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);

        return new EmployeeResource($employee->load(['familyMembers', 'educations', 'experiences']));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        try {
            $employee = $this->employeeService->update(
                $employee,
                $request->validated(), 
                $request->user()
            );

            return new EmployeeResource($employee);
            
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to update employee.',
            ], 500);
        }
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

        try {

            DB::transaction(function () use ($employee) {
                $this->employeeService->delete($employee);
            });

            return response()->json(null, 204);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Failed to delete employee.',
                'error' => $e->getMessage(),
            ], 500);
    }
    }

    public function myProfile(Request $request)
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            return response()->json([
                'message' => 'No employee profile linked to this account.'], 404);
        }

        return new EmployeeResource($employee->load(['familyMembers', 'educations', 'experiences']));
    }
}