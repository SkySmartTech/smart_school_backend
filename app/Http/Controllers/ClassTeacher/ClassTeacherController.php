<?php

namespace App\Http\Controllers\ClassTeacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassTeacher\ClassTeacherCreateRequest;
use App\Repositories\All\ClassTeacher\ClassTeacherInterface;
use Illuminate\Http\Request;

class ClassTeacherController extends Controller
{
    protected $classTeacherInterface;

    public function __construct(ClassTeacherInterface $classTeacherInterface)
    {
        $this->classTeacherInterface = $classTeacherInterface;
    }

    public function index()
    {
        $classTeachers = $this->classTeacherInterface->all();
        return response()->json($classTeachers, 200);
    }

    public function store(ClassTeacherCreateRequest $request){

        $validatedClassTeacher = $request->validated();

        $this->classTeacherInterface->create($validatedClassTeacher);

        return response()->json([
            'message' => 'Class Teacher Created successfully!',
        ], 201);
    }

    public function update(ClassTeacherCreateRequest $request, $id)
    {
        $validatedClassTeacher = $request->validated();

        $updatedClassTeacher = $this->classTeacherInterface->update($id, $validatedClassTeacher);

        if (!$updatedClassTeacher) {
            return response()->json([
                'message' => 'Failed to update class teacher.',
            ], 500);
        }

        return response()->json([
            'message' => 'Class Teacher updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $this->classTeacherInterface->deleteById($id);
        return response()->json();
    }

    public function show($id)
    {
        $classTeacher = $this->classTeacherInterface->findById($id);
        return response()->json($classTeacher, 200);
    }
}
