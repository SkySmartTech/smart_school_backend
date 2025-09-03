<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserTeacherCreateRequest;
use App\Http\Requests\User\UserTeacherUpdateRequest;
use App\Http\Requests\UserTypeRegister\UserTeacherRegisterRequest;
use App\Models\User;
use App\Models\UserTeacher;
use App\Repositories\All\User\UserInterface;
use App\Repositories\All\UserTeacher\UserTeacherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserTeacherController extends Controller
{
    protected $userTeacherInterface;
    protected $userInterface;

    public function __construct(
        UserTeacherInterface $userTeacherInterface,
        UserInterface $userInterface
        )
    {
        $this->userTeacherInterface = $userTeacherInterface;
        $this->userInterface = $userInterface;
    }

    public function store(UserTeacherRegisterRequest $teacherRequest)
    {
        $validated = $teacherRequest->validated();

        foreach ($validated['teacherData'] as $teacher) {
            $this->userTeacherInterface->create($teacher);
        }

        return response()->json([
            'message' => 'User Teacher registered successfully!',
        ], 201);
    }

    public function create(UserTeacherCreateRequest $request)
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
            'status'    => $validatedData['status'], // Default to true if not provided
        ];

        $user = $this->userInterface->create($userData);

        if (!empty($validatedData['teacherData'])) {
            foreach ($validatedData['teacherData'] as $teacher) {
                $teacher['userId']     = $user->id;
                $teacher['userType']   = $user->userType;
                $teacher['modifiedBy'] = Auth::user()->name;

                $this->userTeacherInterface->create($teacher);
            }
        }

        return response()->json([
            'message' => 'New teacher added successfully!',
        ], 201);
    }


    public function showTeachers()
    {
        $teachers = User::where('userType', 'teacher')
                        ->with('teacher')
                        ->get();

        return response()->json($teachers, 200);
    }


    public function update(UserTeacherUpdateRequest $request, string $id)
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

        $this->userTeacherInterface->deleteByUserId($id);

        if (!empty($validatedData['teacherData'])) {
            foreach ($validatedData['teacherData'] as $teacher) {
                $teacher['userId']     = $id;
                $teacher['userType']   = $userData['userType'];
                $teacher['modifiedBy'] = Auth::user()->name;

                $this->userTeacherInterface->create($teacher);
            }
        }

        return response()->json([
            'message' => 'User Teacher updated successfully!',
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
