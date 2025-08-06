<?php

namespace App\Http\Controllers\Marks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marks\MarksCreateRequest;
use App\Repositories\All\Marks\MarksInterface;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    protected $marksInterface;

    public function __construct(MarksInterface $marksInterface)
    {
        $this->marksInterface = $marksInterface;
    }

    public function store(MarksCreateRequest $request)
    {
        $validatedMarks = $request->validated();

        foreach ($validatedMarks['marks'] as $mark) {
            $this->marksInterface->create($mark);
        }

        return response()->json([
            'message' => 'Marks Created successfully!',
        ], 201);
    }

    public function calculateGradeApi(Request $request)
    {
        $marks = $request->input('marks');
        $grade = $this->calculateGrade($marks);

        return response()->json([
            'marks' => $marks,
            'grade' => $grade,
        ], 200);
    }

    private function calculateGrade($marks)
    {
        if ($marks >= 90) {
            return 'A+';
        } elseif ($marks >= 75) {
            return 'A';
        } elseif ($marks >= 65) {
            return 'B';
        } elseif ($marks >= 50) {
            return 'C';
        } elseif ($marks >= 35) {
            return 'S';
        } else {
            return 'F';
        }
    }


}
