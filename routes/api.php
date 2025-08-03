<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use App\Http\Controllers\Grade\GradeController;
use App\Http\Controllers\GradeClass\GradeClassController;
use App\Http\Controllers\Medium\MediumController;
use App\Http\Controllers\Relation\RelationController;
use App\Http\Controllers\School\SchoolController;
use App\Http\Controllers\Subject\SubjectController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserParentController;
use App\Http\Controllers\User\UserStudentController;
use App\Http\Controllers\User\UserTeacherController;
use App\Http\Controllers\UserAccess\UserAccessController;
use App\Http\Controllers\UserRole\UserRoleController;
use App\Http\Controllers\UserType\UserTypeController;
use App\Http\Controllers\UserTypeRegister\UserTypeRegisterController;
use Illuminate\Support\Facades\Route;

Route::post('user-register', [UserRegisterController::class, 'store']);
//Route::post('user-type-register', [UserTypeRegisterController::class, 'store']);
Route::post('user-teacher-register', [UserTeacherController::class, 'store']);
Route::post('user-student-register', [UserStudentController::class, 'store']);
Route::post('user-parent-register', [UserParentController::class, 'store']);

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', [UserController::class, 'show']);
    Route::post('user/{id}/profile-update', [UserController::class, 'profileUpdate']);

    Route::post('add-new-teacher', [UserTeacherController::class, 'create']);
    Route::get('all-teachers', [UserTeacherController::class, 'showTeachers']);
    Route::post('user-teacher/{id}/update', [UserTeacherController::class, 'update']);
    Route::post('user-teacher/{id}/status-update', [UserTeacherController::class, 'updateStatus']);

    Route::post('user/search', [UserController::class, 'search']);

    Route::post('add-new-user-role', [UserRoleController::class, 'store']);
    Route::get('user-roles', [UserRoleController::class, 'index']);
    Route::get('user-role/{id}', [UserRoleController::class, 'show']);
    Route::post('update-user-role/{id}', [UserRoleController::class, 'update']);
    Route::delete('delete-user-role/{id}', [UserRoleController::class, 'destroy']);

    Route::post('add-new-user-type', [UserTypeController::class, 'store']);
    Route::get('user-types', [UserTypeController::class, 'index']);
    Route::get('user-type/{id}', [UserTypeController::class, 'show']);
    Route::post('update-user-type/{id}', [UserTypeController::class, 'update']);
    Route::delete('delete-user-type/{id}', [UserTypeController::class, 'destroy']);

    Route::post('user-access-create', [UserAccessController::class, 'store']);
    Route::get('user-accesses', [UserAccessController::class, 'index']);
    Route::get('user-access/{id}/show', [UserAccessController::class, 'show']);
    Route::post('user-access/{id}/update', [UserAccessController::class, 'update']);
    Route::delete('user-access/{id}/delete', [UserAccessController::class, 'destroy']);

    Route::post('subject-create', [SubjectController::class, 'store']);
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::get('subject/{id}/show', [SubjectController::class, 'show']);
    Route::post('subject/{id}/update', [SubjectController::class, 'update']);
    Route::delete('subject/{id}/delete', [SubjectController::class, 'destroy']);

    Route::post('medium-create', [MediumController::class, 'store']);
    Route::get('mediums', [MediumController::class, 'index']);
    Route::get('medium/{id}/show', [MediumController::class, 'show']);
    Route::post('medium/{id}/update', [MediumController::class, 'update']);
    Route::delete('medium/{id}/delete', [MediumController::class, 'destroy']);

    Route::post('relation-create', [RelationController::class, 'store']);
    Route::get('relations', [RelationController::class, 'index']);
    Route::get('relation/{id}/show', [RelationController::class, 'show']);
    Route::post('relation/{id}/update', [RelationController::class, 'update']);
    Route::delete('relation/{id}/delete', [RelationController::class, 'destroy']);

    Route::post('school-create', [SchoolController::class, 'store']);
    Route::get('schools', [SchoolController::class, 'index']);
    Route::get('school/{id}/show', [SchoolController::class, 'show']);
    Route::post('school/{id}/update', [SchoolController::class, 'update']);
    Route::delete('school/{id}/delete', [SchoolController::class, 'destroy']);

    Route::post('grade-create', [GradeController::class, 'store']);
    Route::get('grades', [GradeController::class, 'index']);
    Route::get('grade/{id}/show', [GradeController::class, 'show']);
    Route::post('grade/{id}/update', [GradeController::class, 'update']);
    Route::delete('grade/{id}/delete', [GradeController::class, 'destroy']);

    Route::post('grade-class-create', [GradeClassController::class, 'store']);
    Route::get('grade-classes', [GradeClassController::class, 'index']);
    Route::get('grade-class/{id}/show', [GradeClassController::class, 'show']);
    Route::post('grade-class/{id}/update', [GradeClassController::class, 'update']);
    Route::delete('grade-class/{id}/delete', [GradeClassController::class, 'destroy']);
});
