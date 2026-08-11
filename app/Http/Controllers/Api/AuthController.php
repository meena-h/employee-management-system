<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            if (!$user->is_active) {
                throw ValidationException::withMessages([
                    'email' => ['Your account has been deactivated.'],
                ]);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                ],
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Login failed.',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully.']);

         } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Logout failed.',
            ], 500);
        }
    }

    public function me(Request $request)
    {
        return response()->json([
            'data' => $request->user()->load('employee'),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user();

            if (!Hash::check($request->validated()['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['The current password is incorrect.'],
                ]);
            }

            $user->update([
                'password' => Hash::make($request->validated()['new_password']),
            ]);

            $user->tokens()->delete();

            return response()->json(['message' => 'Password changed successfully. Please log in again.']);
        
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to change password.',
            ], 500);
        }
    }
}