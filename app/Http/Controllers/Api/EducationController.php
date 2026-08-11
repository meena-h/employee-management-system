<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
use App\Http\Resources\EducationResource;
use App\Models\Employee;
use App\Models\EmployeeEducation;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::findOrFail($request->query('employee_id'));

        $this->authorize('viewAny', [EmployeeEducation::class, $employee]);

        return EducationResource::collection($employee->educations);
    }

    public function store(EducationRequest $request)
    {
        try {
            $employee = Employee::findOrFail($request->validated('employee_id'));

            $data = $request->validated();
            unset($data['employee_id']);

            $education = $employee->educations()->create($data);

            return (new EducationResource($education))
                ->response()
                ->setStatusCode(201);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to create education record.',
            ], 500);
        }

    }

    public function update(EducationRequest $request, EmployeeEducation $education)
    {
        try {
            $education->update($request->validated());

            return new EducationResource($education);
         } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to update education record.',
            ], 500);
        }
    }

    public function destroy(EmployeeEducation $education)
    {
        $this->authorize('delete', $education);

        try {
        $education->delete();

        return response()->json(['message' => 'Education record deleted successfully.'], 200);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to delete education record.',
            ], 500);
        }
    }
}