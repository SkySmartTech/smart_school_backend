<?php

namespace App\Http\Controllers\UserTypeRegister;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTypeRegister\UserParentRegisterRequest;
use App\Http\Requests\UserTypeRegister\UserStudentRegisterRequest;
use App\Http\Requests\UserTypeRegister\UserTeacherRegisterRequest;
use App\Models\userParent;
use App\Models\UserStudent;
use App\Models\UserTeacher;
use Illuminate\Http\Request;

class UserTypeRegisterController extends Controller
{
    public function store(
        Request $request,
        UserTeacherRegisterRequest $teacherRequest,
        UserStudentRegisterRequest $studentRequest,
        UserParentRegisterRequest $parentRequest
        )
    {
        $userType = $request->header('user_type');
        $userId = $request->header('user_id');

        if ($userType === 'teacher') {

            $teacherData = $teacherRequest->validated();
            $teacherData['userId'] = $userId;
            $teacherData['userType'] = $userType;

            UserTeacher::create($teacherData);

            return response()->json([
                'message' => 'User Teacher Registered successfully!',
            ], 201);

        } elseif ($userType === 'student') {

            $studentData = $studentRequest->validated();
            $studentData['userId'] = $userId;
            $studentData['userType'] = $userType;

            UserStudent::create($studentData);

            return response()->json([
                'message' => 'User Student Registered successfully!',
            ], 201);

        } elseif ($userType === 'parent') {

            $parentData = $parentRequest->validated();
            $parentData['userId'] = $userId;
            $parentData['userType'] = $userType;

            UserParent::create($parentData);

            return response()->json([
                'message' => 'User Parent Registered successfully!',
            ], 201);

        } else {
            return response()->json(['message' => 'Invalid user type'], 400);
        }

        return response()->json(['details saved successfully!']);
    }
}
