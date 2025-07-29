<?php

namespace App\Http\Controllers\Grade;

use App\Http\Controllers\Controller;
use App\Http\Requests\Grade\GradeCreateRequest;
use App\Repositories\All\Grade\GradeInterface;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected $gradeInterface;

    public function __construct(GradeInterface $gradeInterface)
    {
        $this->gradeInterface = $gradeInterface;
    }

    public function index()
    {
        $grades = $this->gradeInterface->all();
        return response()->json($grades, 200);
    }

    public function store(GradeCreateRequest $request){

        $validatedGrade = $request->validated();

        $this->gradeInterface->create($validatedGrade);

        return response()->json([
            'message' => 'Grade Created successfully!',
        ], 201);
    }

    public function update(GradeCreateRequest $request, $id)
    {
        $grade = $this->gradeInterface->findById($id);

        if (!$grade) {
            return response()->json([
                'message' => 'Grade not found!',
            ], 404);
        }

        $validatedGrade = $request->validated();

        $updatedGrade = $this->gradeInterface->update($id, $validatedGrade);

        if (!$updatedGrade) {
            return response()->json([
                'message' => 'Failed to update grade.',
            ], 500);
        }

        return response()->json([
            'message' => 'Grade updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $this->gradeInterface->deleteById($id);
        return response()->json();
    }

    public function show($id)
    {
        $grade = $this->gradeInterface->findById($id);
        return response()->json($grade, 200);
    }

}
