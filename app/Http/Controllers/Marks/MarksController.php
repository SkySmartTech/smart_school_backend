<?php

namespace App\Http\Controllers\Marks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marks\MarksCreateRequest;
use App\Models\Marks;
use App\Repositories\All\Marks\MarksInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function managementStaffReportData($year, $grade, $exam)
    {
        $subjectAverages = DB::table('marks')
            ->whereYear('year', $year)
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
            ->whereYear('year', $year)
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
        $year = Carbon::parse(str_replace('.', '-', $endDate))->year;

        $subjectAverages = DB::table('marks')
            ->whereBetween('year', [$startDate, $endDate])
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
            ->whereBetween('year', [$startDate, $endDate])
            ->where('studentGrade', $grade)
            ->where('term', $exam)
            ->where('studentClass', $class)
            ->select(
                DB::raw('YEAR(year) as year'),
                'subject',
                DB::raw('ROUND(AVG(marks), 2) as average_marks')
            )
            ->groupBy(DB::raw('YEAR(year)'), 'subject')
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
            ->where('year', $year)
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
            ->sortByDesc('total_marks')
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

    public function parentReportData(Request $request, $startDate, $endDate, $exam, $month, $studentGrade, $studentClass)
    {
        $admissionNo = $request->query('admission_no');
        $currentYear = Carbon::parse(str_replace('.', '-', $endDate))->year;

        $termYearlyAverages = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->whereBetween('year', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(year) as year'),
                'term',
                DB::raw('ROUND(AVG(marks), 2) as average_marks')
            )
            ->groupBy(DB::raw('YEAR(year)'), 'term')
            ->orderBy('year')
            ->orderByRaw("FIELD(term, 'First', 'Mid', 'End')")
            ->get();

        // group by year
        $nestedAverages = $termYearlyAverages->groupBy('year')->map(function ($terms, $year) {
            return [
                'year'  => $year,
                'terms' => $terms->map(function ($termData) {
                    return [
                        'term'          => $termData->term,
                        'average_marks' => $termData->average_marks
                    ];
                })->values()
            ];
        })->values();

        // Subject averages
        $subjectAveragesQuery = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->where('term', $exam);

        if ($month !== "null") {   // ✅ only apply if not null
            $subjectAveragesQuery->where('month', $month);
        }

        $subjectAverages = $subjectAveragesQuery
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

        // Subject yearly marks
        $subjectYearlyMarks = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->whereBetween('year', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(year) as year'),
                'subject',
                DB::raw('ROUND(AVG(marks), 2) as average_marks')
            )
            ->groupBy(DB::raw('YEAR(year)'), 'subject')
            ->orderBy('year')
            ->get();

        $groupedData = $subjectYearlyMarks->groupBy('subject');

        // Highest marks per subject
        $highestMarksQuery = DB::table('marks')
            ->where('studentGrade', $studentGrade)
            ->where('studentClass', $studentClass)
            ->where('year', $currentYear)
            ->where('term', $exam);

        if ($month !== "null") {
            $highestMarksQuery->where('month', $month);
        }

        $highestMarksPerSubject = $highestMarksQuery
            ->select('subject', DB::raw('MAX(marks) as marks'))
            ->groupBy('subject')
            ->get()
            ->map(function ($item) use ($exam, $month) {
                $recordQuery = DB::table('marks')
                    ->where('term', $exam)
                    ->where('subject', $item->subject)
                    ->where('marks', $item->marks);

                if ($month !== "null") {
                    $recordQuery->where('month', $month);
                }

                $record = $recordQuery->select('subject', 'marks', 'marksGrade')->first();

                return [
                    'subject'    => $record->subject,
                    'marks'      => $record->marks,
                    'marksGrade' => $record->marksGrade
                ];
            });

        // Marks & Grades
        $marksAndGradesQuery = DB::table('marks')
            ->where('studentAdmissionNo', $admissionNo)
            ->where('year', $currentYear)
            ->where('term', $exam);

        if ($month !== "null") {
            $marksAndGradesQuery->where('month', $month);
        }

        $marksAndGrades = $marksAndGradesQuery
            ->select('subject', 'marks', 'marksGrade')
            ->get();

        return response()->json([
            'yearly_term_averages'   => $nestedAverages,
            'subject_marks'          => $subjectAveragesWithPercent,
            'subject_yearly_marks'   => $groupedData,
            'highest_marks_per_subject' => $highestMarksPerSubject,
            'marks_and_grades'       => $marksAndGrades,
        ], 200);
    }

}
