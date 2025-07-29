<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\SubjectCreateRequest;
use App\Repositories\All\Subject\SubjectInterface;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    
    protected $subjectInterface;

    public function __construct(SubjectInterface $subjectInterface)
    {
        $this->subjectInterface = $subjectInterface;
    }

    public function index()
    {
        $subjects = $this->subjectInterface->all();
        return response()->json($subjects, 200);
    }

    public function store(SubjectCreateRequest $request){

        $validatedSubject = $request->validated();

        $this->subjectInterface->create($validatedSubject);

        return response()->json([
            'message' => 'Subject Created successfully!',
        ], 201);
    }

    public function update(SubjectCreateRequest $request, $id)
    {
        $subject = $this->subjectInterface->findById($id);

        if (!$subject) {
            return response()->json([
                'message' => 'Subject not found!',
            ], 404);
        }

        $validatedSubject = $request->validated();

        $updatedSubject = $this->subjectInterface->update($id, $validatedSubject);

        if (!$updatedSubject) {
            return response()->json([
                'message' => 'Failed to update subject.',
            ], 500);
        }

        return response()->json([
            'message' => 'Subject updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $this->subjectInterface->deleteById($id);
        return response()->json();
    }

    public function show($id)
    {
        $subject = $this->subjectInterface->findById($id);
        return response()->json($subject, 200);
    }

}
