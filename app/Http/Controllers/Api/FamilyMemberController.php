<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyMemberRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Models\Employee;
use App\Models\EmployeeFamilyMember;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::findOrFail($request->query('employee_id'));

        $this->authorize('viewAny', [EmployeeFamilyMember::class, $employee]);

        return FamilyMemberResource::collection($employee->familyMembers);
    }

    public function store(FamilyMemberRequest $request)
    {
        try {
            $employee = Employee::findOrFail($request->validated('employee_id'));

            $data = $request->validated();
            unset($data['employee_id']);

            $member = $employee->familyMembers()->create($data);

            return (new FamilyMemberResource($member))
                ->response()
                ->setStatusCode(201);
         } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to create family member.',
            ], 500);
        }        
    }

    public function update(FamilyMemberRequest $request, EmployeeFamilyMember $familyMember)
    {
        try {
            $familyMember->update($request->validated());

            return new FamilyMemberResource($familyMember);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to update family member.',
            ], 500);
        }
    }

    public function destroy(EmployeeFamilyMember $familyMember)
    {
        try {

            $this->authorize('delete', $familyMember);

            $familyMember->delete();

            return response()->json(['message' => 'Family member deleted successfully.'], 200);

         } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to delete family member.',
            ], 500);
        }
    }
}