<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FamilyMemberController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\EmployeeReportController;
use App\Http\Controllers\Api\EmployeeMasterSheetController;
use App\Http\Controllers\Api\EmployeeImportController;
use App\Http\Controllers\Api\UserManagementController;


use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);

    Route::get('/employees/export', [EmployeeMasterSheetController::class, 'export']);
    Route::post('/employees/import', [EmployeeImportController::class, 'import']);   

    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
    Route::match(['put', 'patch'], '/employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

    Route::get('/me/employee', [EmployeeController::class, 'myProfile']);

    Route::get('/employees/{employee}/family-members', [FamilyMemberController::class, 'index']);
    Route::post('/employees/{employee}/family-members', [FamilyMemberController::class, 'store']);
    Route::put('/employees/{employee}/family-members/{familyMember}', [FamilyMemberController::class, 'update']);
    Route::delete('/employees/{employee}/family-members/{familyMember}', [FamilyMemberController::class, 'destroy']);


    Route::get('/employees/{employee}/educations', [EducationController::class, 'index']);
    Route::post('/employees/{employee}/educations', [EducationController::class, 'store']);
    Route::put('/employees/{employee}/educations/{education}', [EducationController::class, 'update']);
    Route::delete('/employees/{employee}/educations/{education}', [EducationController::class, 'destroy']);


    Route::get('/employees/{employee}/experiences', [ExperienceController::class, 'index']);
    Route::post('/employees/{employee}/experiences', [ExperienceController::class, 'store']);
    Route::put('/employees/{employee}/experiences/{experience}', [ExperienceController::class, 'update']);
    Route::delete('/employees/{employee}/experiences/{experience}', [ExperienceController::class, 'destroy']);

    Route::get('/employees/{employee}/biodata-pdf', [EmployeeReportController::class, 'download']);

    Route::get('/users', [UserManagementController::class, 'index']);
    Route::post('/users', [UserManagementController::class, 'store']);
    Route::put('/users/{user}', [UserManagementController::class, 'update']);
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy']);

});