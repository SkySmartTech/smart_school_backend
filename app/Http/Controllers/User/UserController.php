<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Repositories\All\User\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    public function show(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->status != 1) {
            return response()->json(['message' => 'User not available'], 403);
        }

        $userData = $user->toArray();

        return response()->json($userData, 200);

    }

    public function store(UserCreateRequest $request)
    {
        $validatedData = $request->validated();

        $this->userInterface->create($validatedData);

        return response()->json([
            'message' => 'User created successfully!',
        ], 201);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $data = $request->validated();

        $updatedUser = $this->userInterface->update($id, $data);

        return response()->json([
            'message' => 'User updated successfully!',
        ]);
    }

    public function index()
    {
        $users = $this->userInterface->all();
        return response()->json($users, 200);
    }

    public function profileUpdate( $request, $id)
    {
        $data        = $request->validated();
        $updatedUser = $this->userInterface->update($id, $data);

        return response()->json([
            'message' => 'User Profile updated successfully!',
        ]);
    }
}
