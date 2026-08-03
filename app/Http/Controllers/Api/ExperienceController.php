<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Experience\StoreExperienceRequest;
use App\Http\Requests\Experience\UpdateExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Employee;
use App\Models\EmployeeExperience;
use App\Services\ExperienceService;

class ExperienceController extends Controller
{
    public function __construct(protected ExperienceService $experienceService) {}

    public function index(Employee $employee)
    {
        $this->authorize('viewAny', [EmployeeExperience::class, $employee]);

        return ExperienceResource::collection($employee->experiences);
    }

    public function store(StoreExperienceRequest $request, Employee $employee)
    {
        $experience = $this->experienceService->create($employee, $request->validated());

        return (new ExperienceResource($experience))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateExperienceRequest $request, Employee $employee, EmployeeExperience $experience)
    {
        $experience = $this->experienceService->update($experience, $request->validated());

        return new ExperienceResource($experience);
    }

    public function destroy(Employee $employee, EmployeeExperience $experience)
    {
        $this->authorize('delete', $experience);

        $experience->delete();

        return response()->json(['message' => 'Experience record deleted successfully.'], 200);
    }
}