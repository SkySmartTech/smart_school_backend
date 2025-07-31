<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTypeRegister\UserStudentRegisterRequest;
use App\Repositories\All\UserStudent\UserStudentInterface;
use Illuminate\Http\Request;

class UserStudentController extends Controller
{
    protected $userStudentInterface;

    public function __construct(UserStudentInterface $userStudentInterface)
    {
        $this->userStudentInterface = $userStudentInterface;
    }

    public function store(
        Request $request,
        UserStudentRegisterRequest $studentRequest
        )
    {
        $userId = $request->header('user_id');
        $userType = $request->header('user_type');


        $validated = $studentRequest->validated();
        $validated['userId'] = $userId;
        $validated['userType'] = $userType;
        //$validated['modifiedBy'] = Auth::user()->name;

        $this->userStudentInterface->create($validated);

        return response()->json([
            'message' => 'User Student registered successfully!',
        ], 201);
    }
}
