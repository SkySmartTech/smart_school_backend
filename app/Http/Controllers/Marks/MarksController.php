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

    public function calculateGradeApi($marks)
    {
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

    public function managementStaffReportData($year, $grade, $exam)
    {
        $subjectAverages = DB::table('marks')
            ->whereYear('created_at', $year)
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->select('subject', DB::raw('ROUND(AVG(marks), 2) as average_marks'))
            ->groupBy('subject')
            ->get();

        $totalAverageSum = $subjectAverages->sum('average_marks');
        $subjectAveragesWithPercent = $subjectAverages->map(function ($item) use ($totalAverageSum) {
            $percentage = $totalAverageSum > 0
                ? round(($item->average_marks / $totalAverageSum) * 100, 2)
                : 0;
            return [
                'subject' => $item->subject,
                'average_marks' => $item->average_marks,
                'percentage' => $percentage
            ];
        });

        $classMarks = DB::table('marks')
            ->whereYear('created_at', $year)
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->select('studentClass', 'subject', DB::raw('ROUND(AVG(marks), 2) as average_marks'))
            ->groupBy('studentClass', 'subject')
            ->get()
            ->groupBy('studentClass') // Group by class in PHP
            ->map(function ($items) {
                $totalAverageSum = $items->sum('average_marks');

                return $items->map(function ($item) use ($totalAverageSum) {
                    $subjectPercentage = $totalAverageSum > 0
                        ? round(($item->average_marks / $totalAverageSum) * 100, 2)
                        : 0;

                    return [
                        'subject' => $item->subject,
                        'average_mark' => $item->average_marks,
                        'subject_percentage' => $subjectPercentage // Add percentage
                    ];
                });
            });


        return response()->json([
            'subject_marks' => $subjectAveragesWithPercent,
            'class_subject_marks' => $classMarks,
        ], 200);
    }

    public function teacherReportData($startDate, $endDate, $grade, $class, $exam)
    {
        $subjectAverages = DB::table('marks')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->where('studentClass', $class)
            ->select('subject', DB::raw('ROUND(AVG(marks), 2) as average_marks'))
            ->groupBy('subject')
            ->get();


        $totalAverageSum = $subjectAverages->sum('average_marks');
        $subjectAveragesWithPercent = $subjectAverages->map(function ($item) use ($totalAverageSum) {
            $percentage = $totalAverageSum > 0
                ? round(($item->average_marks / $totalAverageSum) * 100, 2)
                : 0;
            return [
                'subject' => $item->subject,
                'average_marks' => $item->average_marks,
                'percentage' => $percentage
            ];
        });

        $yearlySubjectAverages = DB::table('marks')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->where('studentClass', $class)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                'subject',
                DB::raw('ROUND(AVG(marks), 2) as average_marks')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), 'subject')
            ->orderBy('year')
            ->get()
            ->groupBy('year')
            ->map(function ($subjects, $year) {
                $totalAverageSum = $subjects->sum('average_marks');

                $subjectsWithPercent = $subjects->map(function ($item) use ($totalAverageSum) {
                    $percentage = $totalAverageSum > 0
                        ? round(($item->average_marks / $totalAverageSum) * 100, 2)
                        : 0;

                    return [
                        'subject' => $item->subject,
                        'average_marks' => $item->average_marks,
                        'percentage' => $percentage
                    ];
                });

                return [
                    'year' => $year,
                    'subjects' => $subjectsWithPercent->values()
                ];
            })
            ->values();

        $studentMarks = DB::table('marks')
            ->whereBetween('created_at', [$startDate, $endDate])
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
                $subjectCount = $items->count();
                $averageMarks = $subjectCount > 0 ? round($totalMarks / $subjectCount, 2) : 0;

                return [
                    'studentName' => $studentName,
                    'subjects' => $items->map(function ($item) {
                        return [
                            'subject' => $item->subject,
                            'marks' => $item->marks
                        ];
                    })->values(),
                    'total_marks' => $totalMarks,
                    'average_marks' => $averageMarks
                ];
            })
            ->sortByDesc('total_marks') // Sort from highest to lowest
            ->values()
            ->map(function ($item, $index) {
                $item['rank'] = $index + 1;
                return $item;
            });

        return response()->json([
            'subject_marks' => $subjectAveragesWithPercent,
            'yearly_subject_averages' => $yearlySubjectAverages,
            'student_marks' => $studentMarks,
        ], 200);
    }

    public function parentReportData(Request $request, $startDate, $endDate, $exam, $month)
    {
        $admissionNo = $request->query('admission_no');

        $termYearlyAverages = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                'term',
                DB::raw('ROUND(AVG(marks), 2) as average_marks')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), 'term')
            ->orderBy('year')
            ->orderByRaw("FIELD(term, 'First', 'Mid', 'End')")
            ->get();

        // Group by year
        $nestedAverages = $termYearlyAverages->groupBy('year')->map(function ($terms, $year) {
            return [
                'year'  => $year,
                'terms' => $terms->map(function ($termData) {
                    return [
                        'term'          => $termData->term,
                        'average_marks' => $termData->average_marks
                    ];
                })->values() // re-index array
            ];
        })->values();

        $subjectAverages = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->where('term', $exam)
            ->where('month', $month)
            ->select('subject', DB::raw('ROUND(AVG(marks), 2) as average_marks'))
            ->groupBy('subject')
            ->get();


        $totalAverageSum = $subjectAverages->sum('average_marks');
        $subjectAveragesWithPercent = $subjectAverages->map(function ($item) use ($totalAverageSum) {
            $percentage = $totalAverageSum > 0
                ? round(($item->average_marks / $totalAverageSum) * 100, 2)
                : 0;
            return [
                'subject' => $item->subject,
                'average_marks' => $item->average_marks,
                'percentage' => $percentage
            ];
        });

        $subjectYearlyMarks = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                'subject',
                DB::raw('ROUND(AVG(marks), 2) as average_marks')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), 'subject')
            ->orderBy('year')
            ->get();

        $groupedData = $subjectYearlyMarks->groupBy('subject');

        $highestMarksPerSubject = DB::table('marks')
            ->where('term', $exam)
            ->where('month', $month)
            ->select('subject', DB::raw('MAX(marks) as marks'))
            ->groupBy('subject')
            ->get()
            ->map(function ($item) use ($exam, $month) {
                $record = DB::table('marks')
                    ->where('term', $exam)
                    ->where('month', $month)
                    ->where('subject', $item->subject)
                    ->where('marks', $item->marks)
                    ->select('subject', 'marks', 'marksGrade')
                    ->first();

                return [
                    'subject'    => $record->subject,
                    'marks'      => $record->marks,
                    'marksGrade' => $record->marksGrade
                ];
            });

        $marksAndGrades = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->where('term', $exam)
            ->where('month', $month)
            ->select('subject', 'marks', 'marksGrade')
            ->get();

        return response()->json([
            'yearly_term_averages' => $nestedAverages,
            'subject_marks' => $subjectAveragesWithPercent,
            'subject_yearly_marks' => $groupedData,
            'highest_marks_per_subject' => $highestMarksPerSubject,
            'marks_and_grades' => $marksAndGrades,
        ], 200);
    }

}
