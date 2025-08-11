<?php

namespace App\Http\Controllers\Marks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marks\MarksCreateRequest;
use App\Models\Marks;
use App\Repositories\All\Marks\MarksInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function managementStaffReportData(Request $request)
    {
        $year = $request->input('year');
        $grade = $request->input('grade');
        $exam = $request->input('exam');

        $subjectAverages = DB::table('marks')
            ->whereYear('created_at', $year)
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->select('subject', DB::raw('ROUND(AVG(marks), 2) as average_marks'))
            ->groupBy('subject')
            ->get();

        // Class-wise breakdown
        $classMarks = DB::table('marks')
            ->whereYear('created_at', $year)
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->select('studentClass', 'subject', DB::raw('ROUND(AVG(marks), 2) as average_marks'))
            ->groupBy('studentClass', 'subject')
            ->get()
            ->groupBy('studentClass') // Group by class in PHP
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'subject' => $item->subject,
                        'average_mark' => $item->average_marks
                    ];
                });
            });


        return response()->json([
            'subject_marks' => $subjectAverages,
            'class_subject_marks' => $classMarks,
        ], 201);
    }

    public function teacherReportData(Request $request)
    {
        $year = $request->input('year');
        $grade = $request->input('grade');
        $class = $request->input('class');
        $exam = $request->input('exam');

        $subjectAverages = DB::table('marks')
            ->whereYear('created_at', $year)
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->where('studentClass', $class)
            ->select('subject', DB::raw('ROUND(AVG(marks), 2) as average_marks'))
            ->groupBy('subject')
            ->get();

        $studentMarks = DB::table('marks')
            ->whereYear('created_at', $year)
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->where('studentClass', $class)
            ->select(
                'studentName',
                'subject',
                'marks'
            )
            ->orderBy('studentName')
            ->orderBy('subject')
            ->get()
            ->groupBy('studentName')
            ->map(function ($items, $studentName) {
                $totalMarks = $items->sum('marks');
                return [
                    'studentName' => $studentName,
                    'subjects' => $items->map(function ($item) {
                        return [
                            'subject' => $item->subject,
                            'marks' => $item->marks
                        ];
                    })->values(),
                    'total_marks' => $totalMarks,
                ];
            })
            ->sortByDesc('total_marks') // Sort from highest to lowest
            ->values()
            ->map(function ($item, $index) {
                $item['rank'] = $index + 1;
                return $item;
            });

        return response()->json([
            'subject_marks' => $subjectAverages,
            'student_marks' => $studentMarks,
        ], 201);
    }

}
