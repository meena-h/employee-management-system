<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyMember\StoreFamilyMemberRequest;
use App\Http\Requests\FamilyMember\UpdateFamilyMemberRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Models\Employee;
use App\Models\EmployeeFamilyMember;

class FamilyMemberController extends Controller
{
    public function index(Employee $employee)
    {
        $this->authorize('viewAny', [EmployeeFamilyMember::class, $employee]);

        return FamilyMemberResource::collection($employee->familyMembers);
    }

    public function store(StoreFamilyMemberRequest $request, Employee $employee)
    {
        $member = $employee->familyMembers()->create($request->validated());

        return (new FamilyMemberResource($member))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateFamilyMemberRequest $request, Employee $employee, EmployeeFamilyMember $familyMember)
    {
        if ($familyMember->employee_id !== $employee->id) {
            abort(404);
        }

        $familyMember->update($request->validated());

        return new FamilyMemberResource($familyMember);
    }

    public function destroy(Employee $employee, EmployeeFamilyMember $familyMember)
    {
        if ($familyMember->employee_id !== $employee->id) {
            abort(404);
        }

        $this->authorize('delete', $familyMember);

        $familyMember->delete();

        return response()->json(['message' => 'Family member deleted successfully.'], 200);
    }
}