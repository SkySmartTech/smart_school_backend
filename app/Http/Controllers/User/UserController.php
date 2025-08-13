<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserProfileUpdateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\UserTypeRegister\UserParentRegisterRequest;
use App\Http\Requests\UserTypeRegister\UserStudentRegisterRequest;
use App\Http\Requests\UserTypeRegister\UserTeacherRegisterRequest;
use App\Models\User;
use App\Models\UserParent;
use App\Repositories\All\User\UserInterface;
use App\Repositories\All\UserAccess\UserAccessInterface;
use App\Repositories\All\UserStudent\UserStudentInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userInterface;
    protected $userAccessInterface;
    protected $userStudentInterface;

    public function __construct(
        UserInterface $userInterface,
        UserAccessInterface $userAccessInterface,
        UserStudentInterface $userStudentInterface
        )
    {
        $this->userInterface = $userInterface;
        $this->userAccessInterface = $userAccessInterface;
        $this->userStudentInterface = $userStudentInterface;
    }

    public function show(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->status != 1) {
            return response()->json(['message' => 'User not available'], 403);
        }

        $userData = $user->toArray();

        switch (strtolower($user->userType)) {
            case 'teacher':
                $teacherData = $user->teacher; // eager load teacher data
                $userData['teacher_data'] = $teacherData;
                break;

            case 'student':
                $studentData = $user->student;
                $userData['student_data'] = $studentData;
                break;

            case 'parent':
                $parentData = $user->parent()->with('student.user')->first();

                if ($parentData && $parentData->student) {
                    $userData['parent_data'] = [
                        'parent_info' => $parentData,
                        'student_info' => [
                            'name'  => $parentData->student->user->name ?? null, // if linked to User
                            'grade' => $parentData->student->studentGrade,
                            'class' => $parentData->student->studentClass,
                        ]
                    ];
                } else {
                    $userData['parent_data'] = null;
                }
                break;

            default:
                $userData['type_data'] = null;
        }

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

    public function profileUpdate(
        UserProfileUpdateRequest $request,
        UserTeacherRegisterRequest $teacherRequest,
        UserStudentRegisterRequest $studentRequest,
        UserParentRegisterRequest $parentRequest,
        $id
    ) {
        $commonData = $request->validated();
        $user = $this->userInterface->update($id, $commonData);

        $user = User::find($id);

        switch (strtolower($user->userType)) {
            case 'teacher':
                $teacherData = $teacherRequest->validated();
                $user->teacher()->update($teacherData);
                break;

            case 'student':
                $studentData = $studentRequest->validated();
                $user->student()->update($studentData);
                break;

            case 'parent':
                $parentData = $parentRequest->validated();
                $user->parent()->update($parentData);
                break;

            default:
                return response()->json(['message' => 'Invalid user type'], 400);
        }
        return response()->json([
            'message' => 'User Profile updated successfully!',
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $users = $this->userInterface->search($keyword);
        $admissions = $this->userStudentInterface->search($keyword);

        $userData = $users->map(function ($user) {
            $userArray = $user->toArray();

            return $userArray;
        });


        $admissionData = $admissions->map(function ($user) {
            $userArray = $user->toArray();

            return $userArray;
        });

        return response()->json([
            'users' => $userData,
            'admissions' => $admissionData,
        ], 200);
    }
}
