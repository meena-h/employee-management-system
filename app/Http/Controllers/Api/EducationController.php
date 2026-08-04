<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Education\StoreEducationRequest;
use App\Http\Requests\Education\UpdateEducationRequest;
use App\Http\Resources\EducationResource;
use App\Models\Employee;
use App\Models\EmployeeEducation;

class EducationController extends Controller
{
    public function index(Employee $employee)
    {
        $this->authorize('viewAny', [EmployeeEducation::class, $employee]);

        return EducationResource::collection($employee->educations);
    }

    public function store(StoreEducationRequest $request, Employee $employee)
    {
        $education = $employee->educations()->create($request->validated());

        return (new EducationResource($education))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateEducationRequest $request, Employee $employee, EmployeeEducation $education)
    {
        if ($education->employee_id !== $employee->id) {
            abort(404);
        }

        $education->update($request->validated());

        return new EducationResource($education);
    }

    public function destroy(Employee $employee, EmployeeEducation $education)
    {
        if ($education->employee_id !== $employee->id) {
            abort(404);
        }

        $this->authorize('delete', $education);

        $education->delete();

        return response()->json(['message' => 'Education record deleted successfully.'], 200);
    }
}