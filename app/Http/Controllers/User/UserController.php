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
use Illuminate\Support\Facades\Auth;
use App\Models\UserParent;
use App\Repositories\All\User\UserInterface;
use App\Repositories\All\UserAccess\UserAccessInterface;
use App\Repositories\All\UserParent\UserParentInterface;
use App\Repositories\All\UserStudent\UserStudentInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    protected $userInterface;
    protected $userAccessInterface;
    protected $userStudentInterface;
    protected $userParentInterface;

    public function __construct(
        UserInterface $userInterface,
        UserAccessInterface $userAccessInterface,
        UserStudentInterface $userStudentInterface,
        UserParentInterface $userParentInterface,

        )
    {
        $this->userInterface = $userInterface;
        $this->userAccessInterface = $userAccessInterface;
        $this->userStudentInterface = $userStudentInterface;
        $this->userParentInterface = $userParentInterface;
    }

    public function show(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->status != 1) {
            return response()->json(['message' => 'User not available'], 403);
        }

        $userData = $user->toArray();

        $role = $user->userRole;

        $access = DB::table('user_accesses')
                    ->where('userType', $role)
                    ->pluck('permissionObject');

        switch (strtolower($user->userType)) {
            case 'teacher':
                $teacherData = $user->teacher;
                $userData['teacher_data'] = $teacherData ? $teacherData->toArray() : [];
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

        $userData['access'] = $access ?? [];

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

    public function profileUpdate(Request $request, $id)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'birthDay' => 'nullable|date',
            'contact' => 'required|string|max:15',
            'userType' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'username' => 'required|string|max:255',
        ]);

        $this->userInterface->update($id, $validatedData);

        // Handle extra profile data depending on role
        switch (strtolower($user->userType)) {
            case 'teacher':
                $teacherData = $request->validate([
                    'teacherData'                  => 'nullable|array',
                    'teacherData.*.teacherGrade'   => 'nullable|string|max:255',
                    'teacherData.*.teacherClass'   => 'nullable|string|max:255',
                    'teacherData.*.subject'        => 'nullable|string|max:255',
                    'teacherData.*.medium'         => 'nullable|string|max:255',
                    'teacherData.*.staffNo'        => 'nullable|string|max:255',
                    'teacherData.*.userId'         => 'nullable|integer|exists:users,id',
                    'teacherData.*.userType'       => 'nullable|string|max:255',
                ]);

                // Remove old teacher records if needed
                $user->teacher()->delete();

                // Insert new teacher records
                if (!empty($teacherData['teacherData'])) {
                    foreach ($teacherData['teacherData'] as $teacher) {
                        $teacher['modifiedBy']    = Auth::user()->name;
                        $user->teacher()->create($teacher);
                    }
                }

                break;

            case 'student':
                $studentData = $request->validate([
                    'studentGrade' => 'nullable|string|max:255',
                    'studentAdmissionNo' => 'nullable|string|max:255',
                ]);
                $studentData['modifiedBy']    = Auth::user()->name;

                $this->userStudentInterface->updateByUserId($id, $studentData);

                break;

            case 'parent':
                $parentData = $request->validate([
                    'studentAdmissionNo' => 'nullable|string|max:255',
                    'parentContact' => 'nullable|string|max:15',
                    'profession' => 'nullable|string|max:255',
                    'relation' => 'nullable|string|max:255'
                ]);
                $parentData['modifiedBy']    = Auth::user()->name;
                $this->userParentInterface->updateByUserId($id, $parentData);

                break;
        }

        return response()->json([
            'message' => 'Profile updated successfully',
        ], 200);
    }

    public function search(Request $request)
    {
        
    }
}
