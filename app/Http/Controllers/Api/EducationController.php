<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
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

    public function store(EducationRequest $request, Employee $employee)
    {
        $education = $employee->educations()->create($request->validated());

        return (new EducationResource($education))
            ->response()
            ->setStatusCode(201);
    }

    public function update(EducationRequest $request, EmployeeEducation $education)
    {
        $education->update($request->validated());

        return new EducationResource($education);
    }

    public function destroy(EmployeeEducation $education)
    {
        $this->authorize('delete', $education);

        $education->delete();

        return response()->json(['message' => 'Education record deleted successfully.'], 200);
    }
}