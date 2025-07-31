<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTypeRegister\UserTeacherRegisterRequest;
use App\Repositories\All\UserTeacher\UserTeacherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTeacherController extends Controller
{
    protected $userTeacherInterface;

    public function __construct(UserTeacherInterface $userTeacherInterface)
    {
        $this->userTeacherInterface = $userTeacherInterface;
    }

    public function store(
        Request $request,
        UserTeacherRegisterRequest $teacherRequest
        )
    {
        $userId = $request->header('user_id');
        $userType = $request->header('user_type');


        $validated = $teacherRequest->validated();
        $validated['userId'] = $userId;
        $validated['userType'] = $userType;
        //$validated['modifiedBy'] = Auth::user()->name;

        $this->userTeacherInterface->create($validated);

        return response()->json([
            'message' => 'User Teacher registered successfully!',
        ], 201);
    }
}
