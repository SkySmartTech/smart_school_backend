<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStudentCreateRequest;
use App\Http\Requests\User\UserStudentUpdateRequest;
use App\Http\Requests\UserTypeRegister\UserStudentRegisterRequest;
use App\Models\User;
use App\Repositories\All\User\UserInterface;
use App\Repositories\All\UserStudent\UserStudentInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserStudentController extends Controller
{
    protected $userStudentInterface;
    protected $userInterface;

    public function __construct(
        UserStudentInterface $userStudentInterface,
        UserInterface $userInterface
        )
    {
        $this->userStudentInterface = $userStudentInterface;
        $this->userInterface = $userInterface;
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

        $this->userStudentInterface->create($validated);

        return response()->json([
            'message' => 'User Student registered successfully!',
        ], 201);
    }

    public function showStudents()
    {
        $students = User::where('userType', 'student')
                        ->with('student')
                        ->get();

        return response()->json($students, 200);
    }

    public function create(UserStudentCreateRequest $request)
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

        $studentData = [
            'userId'        => $user->id,
            'userType'      => $user->userType,
            'studentGrade'  => $validatedData['studentGrade'],
            'medium'       => $validatedData['medium'],
            'studentClass'  => $validatedData['studentClass'],
            'studentAdmissionNo' => $validatedData['studentAdmissionNo'],
            'parentNo'     => $validatedData['parentNo'],
            'parentProfession' => $validatedData['parentProfession'],
            'modifiedBy'    => Auth::user()->name,
        ];

        $this->userStudentInterface->create($studentData);

        return response()->json([
            'message' => 'New student added successfully!',
        ], 201);
    }

    public function update(UserStudentUpdateRequest $request, string $id)
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


        $teacherData = [
            'userType'      => $validatedData['userType'],
            'teacherGrades' => $validatedData['teacherGrades'],
            'teacherClass'  => $validatedData['teacherClass'],
            'subjects'      => $validatedData['subjects'],
            'staffNo'       => $validatedData['staffNo'],
            'medium'        => $validatedData['medium'],
            'modifiedBy'    => Auth::user()->name,
        ];

        $this->userStudentInterface->updateByUserId($id, $teacherData);

        return response()->json([
            'message' => 'User Student updated successfully!',
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

    public function showAdmissionData($grade, $class)
    {
        $admissionData = User::where('userType', 'student')
            ->whereHas('student', function ($query) use ($grade, $class) {
                $query->where('studentGrade', $grade)
                    ->where('studentClass', $class);
            })
            ->with(['student' => function ($query) {
                $query->select('userId', 'studentAdmissionNo'); // ensure only necessary fields
            }])
            ->select('id', 'name') // only id (for relation) and name
            ->get();

        $transformed = $admissionData->map(function ($user) {
            return [
                'name' => $user->name,
                'studentAdmissionNo' => $user->student->studentAdmissionNo ?? null,
            ];
        });

        return response()->json($transformed, 200);
    }

    public function searchAdmissionData($grade, $class, Request $request)
    {
        $keyword = $request->input('keyword');

        $admissionData = User::where('userType', 'student')
            ->whereHas('student', function ($query) use ($grade, $class) {
                $query->where('studentGrade', $grade)
                    ->where('studentClass', $class);
            })
            ->with(['student' => function ($query) {
                $query->select('userId', 'studentAdmissionNo');
            }])
            ->select('id', 'name')
            ->get();

        $transformed = $admissionData->map(function ($user) {
            return [
                'name' => $user->name,
                'studentAdmissionNo' => $user->student->studentAdmissionNo ?? null,
            ];
        });

        if ($keyword) {
            $transformed = $transformed->filter(function ($item) use ($keyword) {
                return str_contains(strtolower($item['name']), strtolower($keyword)) ||
                    str_contains(strtolower($item['studentAdmissionNo']), strtolower($keyword));
            })->values();
        }

        return response()->json($transformed, 200);
    }



}
