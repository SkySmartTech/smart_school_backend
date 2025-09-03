<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserParentCreateRequest;
use App\Http\Requests\User\UserParentUpdateRequest;
use App\Http\Requests\UserTypeRegister\UserParentRegisterRequest;
use App\Models\User;
use App\Repositories\All\User\UserInterface;
use App\Repositories\All\UserParent\UserParentInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserParentController extends Controller
{
    protected $userParentInterface;
    protected $userInterface;

    public function __construct(
        UserParentInterface $userParentInterface,
        UserInterface $userInterface
        )
    {
        $this->userParentInterface = $userParentInterface;
        $this->userInterface = $userInterface;
    }

    public function store(
        Request $request,
        UserParentRegisterRequest $ParentRequest)
    {
        $userId = $request->header('userId');
        $userType = $request->header('userType');

        $validated = $ParentRequest->validated();
        $validated['userId'] = $userId;
        $validated['userType'] = $userType;

        $this->userParentInterface->create($validated);

        return response()->json([
            'message' => 'User Parent registered successfully!',
        ], 201);
    }

    public function showParents()
    {
        $parents = User::where('userType', 'parent')
                        ->with('parent')
                        ->get();

        return response()->json($parents, 200);
    }

    public function create(UserParentCreateRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $userData = [
            'name'      => $validatedData['name'],
            'address'   => $validatedData['address'],
            'email'     => $validatedData['email'],
            'birthDay'  => $validatedData['birthDay'],
            'contact'   => $validatedData['contact'],
            'userType'  => $validatedData['userType'],
            'gender'    => $validatedData['gender'],
            'location'  => $validatedData['location'],
            'username'  => $validatedData['username'],
            'password'  => $validatedData['password'],
            'photo'     => $validatedData['photo'],
            'userRole'  => $validatedData['userRole'],
            'status'    => $validatedData['status'],
        ];

        $user = $this->userInterface->create($userData);

        $parentData = [
            'userId'        => $user->id,
            'userType'      => $user->userType,
            'studentAdmissionNo' => $validatedData['studentAdmissionNo'],
            'parentContact' => $validatedData['parentContact'],
            'profession'  => $validatedData['profession'],
            'relation'      => $validatedData['relation'],
        ];

        $this->userParentInterface->create($parentData);

        return response()->json([
            'message' => 'New parent added successfully!',
        ], 201);
    }

    public function update(UserParentUpdateRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $userData = [
            'name'      => $validatedData['name'],
            'address'   => $validatedData['address'],
            'email'     => $validatedData['email'],
            'birthDay'  => $validatedData['birthDay'],
            'contact'   => $validatedData['contact'],
            'userType'  => $validatedData['userType'],
            'gender'    => $validatedData['gender'],
            'location'  => $validatedData['location'],
            'username'  => $validatedData['username'],
            'photo'     => $validatedData['photo'],
            'userRole'  => $validatedData['userRole'],
            'status'    => $validatedData['status'],
        ];

        $this->userInterface->update($id, $userData);


        $parentData = [
            'userType'      => $validatedData['userType'],
            'studentAdmissionNo' => $validatedData['studentAdmissionNo'],
            'parentContact' => $validatedData['parentContact'],
            'profession'  => $validatedData['profession'],
            'relation'      => $validatedData['relation'],
        ];

        $this->userParentInterface->updateByUserId($id, $parentData);

        return response()->json([
            'message' => 'User Parent updated successfully!',
        ], 200);
    }

    public function updateStatus($id)
    {
        $user = $this->userInterface->findById($id);

        $user->status = false;
        $user->save();

        return response()->json([
            'message' => 'User deactivated successfully.',
        ]);
    }
}
