<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTypeRegister\UserParentRegisterRequest;
use App\Repositories\All\UserParent\UserParentInterface;
use Illuminate\Http\Request;

class UserParentController extends Controller
{
    protected $userParentInterface;

    public function __construct(UserParentInterface $userParentInterface)
    {
        $this->userParentInterface = $userParentInterface;
    }

    public function store(
        Request $request,
        UserParentRegisterRequest $ParentRequest)
    {
        $userId = $request->header('user_id');
        $userType = $request->header('user_type');

        $validated = $ParentRequest->validated();
        $validated['userId'] = $userId;
        $validated['userType'] = $userType;

        $this->userParentInterface->create($validated);

        return response()->json([
            'message' => 'User Parent registered successfully!',
        ], 201);
    }
}
