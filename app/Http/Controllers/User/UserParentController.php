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
        $validated = $ParentRequest->validated();

        foreach ($validated['parentData'] as $parent) {
            $this->userParentInterface->create($parent);
        }

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
            'name'      => $validatedData['name'] ?? null,
            'address'   => $validatedData['address'] ?? null,
            'email'     => $validatedData['email'] ?? null,
            'birthDay'  => $validatedData['birthDay'] ?? null,
            'contact'   => $validatedData['contact'] ?? null,
            'userType'  => $validatedData['userType'] ?? null,
            'gender'    => $validatedData['gender'] ?? null,
            'location'  => $validatedData['location'] ?? null,
            'username'  => $validatedData['username'] ?? null,
            'password'  => $validatedData['password'] ?? null,
            'photo'     => $validatedData['photo'] ?? null,
            'userRole'  => $validatedData['userRole'] ?? null,
            'status'    => $validatedData['status'] ?? null,
        ];

        $user = $this->userInterface->create($userData);

        if (!empty($validatedData['parentData'])) {
            foreach ($validatedData['parentData'] as $parent) {
                $parent['userId']     = $user->id;
                $parent['userType']   = $user->userType;
                $parent['modifiedBy'] = Auth::user()->name;

                $this->userParentInterface->create($parent);
            }
        }

        return response()->json([
            'message' => 'New parent added successfully!',
        ], 201);
    }

    public function parentDelete($id)
    {
        $this->userInterface->deleteById($id);
        $this->userParentInterface->deleteByUserId($id);
        return response()->json();
    }

    public function update(UserParentUpdateRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $userData = [
            'name'      => $validatedData['name'] ?? null,
            'address'   => $validatedData['address'] ?? null,
            'email'     => $validatedData['email'] ?? null,
            'birthDay'  => $validatedData['birthDay'] ?? null,
            'contact'   => $validatedData['contact'] ?? null,
            'userType'  => $validatedData['userType'] ?? null,
            'gender'    => $validatedData['gender'] ?? null,
            'location'  => $validatedData['location'] ?? null,
            'username'  => $validatedData['username'] ?? null,
            'photo'     => $validatedData['photo'] ?? null,
            'userRole'  => $validatedData['userRole'] ?? null,
            'status'    => $validatedData['status'] ?? null,
        ];

        $this->userInterface->update($id, $userData);

        $this->userParentInterface->deleteByUserId($id);

        if (!empty($validatedData['parentData'])) {
            foreach ($validatedData['parentData'] as $parent) {
                $parent['userId']     = $id;
                $parent['userType']   = $userData['userType'];
                $parent['modifiedBy'] = Auth::user()->name;

                $this->userParentInterface->create($parent);
            }
        }

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

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = User::where('userType', 'parent')->with('parent');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhere('gender', 'like', '%' . $keyword . '%')
                ->orWhere('username', 'like', '%' . $keyword . '%')
                ->orWhere('address', 'like', '%' . $keyword . '%')
                ->orWhereHas('parent', function ($subQuery) use ($keyword) {
                    $subQuery->where('studentAdmissionNo', 'like', '%' . $keyword . '%')
                            ->orWhere('profession', 'like', '%' . $keyword . '%')
                            ->orWhere('relation', 'like', '%' . $keyword . '%')
                            ->orWhere('parentContact', 'like', '%' . $keyword . '%');
                });
            });
        }

        $parents = $query->get();

        return response()->json($parents, 200);
    }
}
