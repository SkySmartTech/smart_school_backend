<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\SchoolCreateRequest;
use App\Repositories\All\School\SchoolInterface;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    protected $schoolInterface;

    public function __construct(SchoolInterface $schoolInterface)
    {
        $this->schoolInterface = $schoolInterface;
    }

    public function index()
    {
        $schools = $this->schoolInterface->all();
        return response()->json($schools, 200);
    }

    public function store(SchoolCreateRequest $request){

        $validatedSchool = $request->validated();

        $this->schoolInterface->create($validatedSchool);

        return response()->json([
            'message' => 'School Created successfully!',
        ], 201);
    }

    public function update(SchoolCreateRequest $request, $id)
    {
        $school = $this->schoolInterface->findById($id);

        if (!$school) {
            return response()->json([
                'message' => 'School not found!',
            ], 404);
        }

        $validatedSchool = $request->validated();

        $updatedSchool = $this->schoolInterface->update($id, $validatedSchool);

        if (!$updatedSchool) {
            return response()->json([
                'message' => 'Failed to update school.',
            ], 500);
        }

        return response()->json([
            'message' => 'School updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $this->schoolInterface->deleteById($id);
        return response()->json();
    }

    public function show($id)
    {
        $school = $this->schoolInterface->findById($id);
        return response()->json($school, 200);
    }

}
