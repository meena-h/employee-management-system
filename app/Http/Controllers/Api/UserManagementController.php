<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        return UserResource::collection(User::with('employee')->paginate(15));
    }

    public function store(StoreUserRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => Hash::make($request->validated('password')),
                'role' => $request->validated('role'),
                'is_active' => true,
            ]);

            if ($request->filled('employee_id')) {
                Employee::where('id', $request->validated('employee_id'))
                    ->update(['user_id' => $user->id]);
            }

            return $user;
        });

        return (new UserResource($user->load('employee')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $employeeId = $data['employee_id'] ?? null;
        unset($data['employee_id']);

        $user->update($data);

        if (array_key_exists('employee_id', $request->validated())) {
            // Unlink any employee currently pointing to this user
            Employee::where('user_id', $user->id)->update(['user_id' => null]);

            if ($employeeId) {
                Employee::where('id', $employeeId)->update(['user_id' => $user->id]);
            }
        }

        return new UserResource($user->fresh('employee'));
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot deactivate your own account.'], 422);
        }

        $user->update(['is_active' => false]);
        $user->tokens()->delete(); // revoke all active sessions immediately

        return response()->json(['message' => 'User deactivated successfully.']);
    }

    private function authorizeAdmin(): void
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'This action is unauthorized.');
        }
    }
}