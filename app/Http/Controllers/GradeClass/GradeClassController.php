<?php

namespace App\Http\Controllers\GradeClass;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeClass\GradeClassCreateRequest;
use App\Repositories\All\GradeClass\GradeClassInterface;
use Illuminate\Http\Request;

class GradeClassController extends Controller
{
    protected $gradeClassInterface;

    public function __construct(GradeClassInterface $gradeClassInterface)
    {
        $this->gradeClassInterface = $gradeClassInterface;
    }

    public function index()
    {
        $gradeClasses = $this->gradeClassInterface->all();
        return response()->json($gradeClasses, 200);
    }

    public function store(GradeClassCreateRequest $request){

        $validatedGradeClass = $request->validated();

        $this->gradeClassInterface->create($validatedGradeClass);

        return response()->json([
            'message' => 'Grade Class Created successfully!',
        ], 201);
    }

    public function update(GradeClassCreateRequest $request, $id)
    {
        $gradeClass = $this->gradeClassInterface->findById($id);

        if (!$gradeClass) {
            return response()->json([
                'message' => 'Grade Class not found!',
            ], 404);
        }

        $validatedGradeClass = $request->validated();

        $updatedGradeClass = $this->gradeClassInterface->update($id, $validatedGradeClass);

        if (!$updatedGradeClass) {
            return response()->json([
                'message' => 'Failed to update grade class.',
            ], 500);
        }

        return response()->json([
            'message' => 'Grade Class updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $this->gradeClassInterface->deleteById($id);
        return response()->json();
    }

    public function show($id)
    {
        $gradeClass = $this->gradeClassInterface->findById($id);
        return response()->json($gradeClass, 200);
    }

}
