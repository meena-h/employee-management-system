<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FamilyMemberController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\EmployeeReportController;
use App\Http\Controllers\Api\EmployeeMasterExportController;
use App\Http\Controllers\Api\EmployeeImportController;
use App\Http\Controllers\Api\UserManagementController;


use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);

    Route::get('/employees/export', [EmployeeMasterExportController::class, 'export']);
    Route::post('/employees/import', [EmployeeImportController::class, 'import']);   

    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::post('/employee', [EmployeeController::class, 'store']);
    Route::get('/employee/{employee}', [EmployeeController::class, 'show']);
    Route::match(['put', 'patch'], '/employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employee/{employee}', [EmployeeController::class, 'destroy']);

    Route::get('/me/employee', [EmployeeController::class, 'myProfile']);

    Route::get('/employee-family-members', [FamilyMemberController::class, 'index']);
    Route::post('/employee-family-members', [FamilyMemberController::class, 'store']);
    Route::put('/employee-family-members/{familyMember}', [FamilyMemberController::class, 'update']);
    Route::delete('/employee-family-members/{familyMember}', [FamilyMemberController::class, 'destroy']);


    Route::get('/employee-educations', [EducationController::class, 'index']);
    Route::post('/employee-educations', [EducationController::class, 'store']);
    Route::put('/employee-educations/{education}', [EducationController::class, 'update']);
    Route::delete('/employee-educations/{education}', [EducationController::class, 'destroy']);


    Route::get('/employee-experiences', [ExperienceController::class, 'index']);
    Route::post('/employee-experiences', [ExperienceController::class, 'store']);
    Route::put('/employee-experiences/{experience}', [ExperienceController::class, 'update']);
    Route::delete('/employee-experiences/{experience}', [ExperienceController::class, 'destroy']);

    Route::get('/employee/{employee}/biodata-pdf', [EmployeeReportController::class, 'download']);

    Route::get('/users', [UserManagementController::class, 'index']);
    Route::post('/users', [UserManagementController::class, 'store']);
    Route::put('/users/{user}', [UserManagementController::class, 'update']);
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy']);

});