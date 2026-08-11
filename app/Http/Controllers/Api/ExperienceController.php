<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Employee;
use App\Models\EmployeeExperience;
use App\Services\ExperienceService;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function __construct(protected ExperienceService $experienceService) {}

    public function index(Request $request)
    {
        $employee = Employee::findOrFail($request->query('employee_id'));

        $this->authorize('viewAny', [EmployeeExperience::class, $employee]);

        return ExperienceResource::collection($employee->experiences);
    }

    public function store(ExperienceRequest $request)
    {
        try {
            $employee = Employee::findOrFail($request->validated('employee_id'));

            $data = $request->validated();
            unset($data['employee_id']);

            $experience = $this->experienceService->create($employee, $data);

            return (new ExperienceResource($experience))
                ->response()
                ->setStatusCode(201);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to create experience record.',
            ], 500);
        }
    }

    public function update(ExperienceRequest $request, EmployeeExperience $experience)
    {
        try {

        $experience = $this->experienceService->update($experience, $request->validated());

        return new ExperienceResource($experience);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to update experience record.',
            ], 500);
        }
    }

    public function destroy(EmployeeExperience $experience)
    {
        $this->authorize('delete', $experience);
        
        try {
            $experience->delete();

            return response()->json(['message' => 'Experience record deleted successfully.'], 200);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to delete experience record.',
            ], 500);
        }
    }
}