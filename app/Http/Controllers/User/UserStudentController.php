<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStudentCreateRequest;
use App\Http\Requests\User\UserStudentUpdateRequest;
use App\Http\Requests\UserTypeRegister\StudentMultiCreateRequest;
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
        $userId = $request->header('userId');
        $userType = $request->header('userType');


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
            'studentGrade' => $validatedData['studentGrade'],
            'medium'  => $validatedData['medium'],
            'studentClass'      => $validatedData['studentClass'],
            'studentAdmissionNo'       => $validatedData['studentAdmissionNo'],
            'parentNo'        => $validatedData['parentNo'],
            'parentProfession' => $validatedData['parentProfession'],
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


    public function multiCreate(StudentMultiCreateRequest $request)
    {
        $validatedData = $request->validated();

        foreach ($validatedData['studentData'] as $student) {
        // Hash password
            $student['password'] = Hash::make($student['password']);

            // Prepare user data
            $userData = [
                'name'      => $student['name'],
                'address'   => $student['address'] ?? null,
                'email'     => $student['email'],
                'birthDay'  => $student['birthDay'] ?? null,
                'contact'   => $student['contact'] ?? null,
                'userType'  => $student['userType'],
                'gender'    => $student['gender'] ?? null,
                'location'  => $student['location'] ?? null,
                'username'  => $student['username'],
                'password'  => $student['password'],
                'photo'     => $student['photo'] ?? null,
                'userRole'  => $student['userRole'] ?? 'student',
                'status'    => $student['status'] ?? true,
            ];

            // Create user
            $user = $this->userInterface->create($userData);

            // Prepare student-specific data
            $studentData = [
                'userId'             => $user->id,
                'userType'           => $user->userType,
                'studentGrade'       => $student['studentGrade'] ?? null,
                'studentClass'       => $student['studentClass'] ?? null,
                'subject'            => $student['subject'] ?? null,
                'medium'             => $student['medium'] ?? null,
                'studentAdmissionNo' => $student['studentAdmissionNo'] ?? null,
                'modifiedBy'         => Auth::user()->name,
            ];

            // Save student data
            $this->userStudentInterface->create($studentData);
        }

        return response()->json([
            'message' => 'Students Created successfully!',
        ], 201);
    }


}
