<?php

namespace App\Http\Controllers\Medium;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medium\MediumCreateRequest;
use App\Repositories\All\Medium\MediumInterface;
use Illuminate\Http\Request;

class MediumController extends Controller
{
    protected $mediumInterface;

    public function __construct(MediumInterface $mediumInterface)
    {
        $this->mediumInterface = $mediumInterface;
    }

    public function index()
    {
        $mediums = $this->mediumInterface->all();
        return response()->json($mediums, 200);
    }

    public function store(MediumCreateRequest $request){

        $validatedMedium = $request->validated();

        $this->mediumInterface->create($validatedMedium);

        return response()->json([
            'message' => 'Medium Created successfully!',
        ], 201);
    }

    public function update(MediumCreateRequest $request, $id)
    {
        $medium = $this->mediumInterface->findById($id);

        if (!$medium) {
            return response()->json([
                'message' => 'Medium not found!',
            ], 404);
        }

        $validatedMedium = $request->validated();

        $updatedMedium = $this->mediumInterface->update($id, $validatedMedium);

        if (!$updatedMedium) {
            return response()->json([
                'message' => 'Failed to update medium.',
            ], 500);
        }

        return response()->json([
            'message' => 'Medium updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $this->mediumInterface->deleteById($id);
        return response()->json();
    }

    public function show($id)
    {
        $medium = $this->mediumInterface->findById($id);
        return response()->json($medium, 200);
    }

}
