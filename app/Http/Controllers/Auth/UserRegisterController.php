<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Repositories\All\User\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    public function store(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = $this->userInterface->create($validated);

        return response()->json([
            'message' => 'User registered successfully!',
            'userId'    => $user->id,
            'userType'  => $user->userType ?? null,
        ], 201);
    }

    public function destroy(Request $request)
    {
        $id = $request->header('user_id');

        $this->userInterface->deleteById($id);
        return response()->json();
    }
}
