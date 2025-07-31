<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Relation\RelationCreateRequest;
use App\Repositories\All\Relation\RelationInterface;
use Illuminate\Http\Request;

class RelationController extends Controller
{
    protected $relationInterface;

    public function __construct(RelationInterface $relationInterface)
    {
        $this->relationInterface = $relationInterface;
    }

    public function index()
    {
        $relations = $this->relationInterface->all();
        return response()->json($relations, 200);
    }

    public function store(RelationCreateRequest $request){

        $validatedRelation = $request->validated();

        $this->relationInterface->create($validatedRelation);

        return response()->json([
            'message' => 'Relation Created successfully!',
        ], 201);
    }

    public function update(RelationCreateRequest $request, $id)
    {
        $relation = $this->relationInterface->findById($id);

        if (!$relation) {
            return response()->json([
                'message' => 'Relation not found!',
            ], 404);
        }

        $validatedRelation = $request->validated();

        $updatedRelation = $this->relationInterface->update($id, $validatedRelation);

        if (!$updatedRelation) {
            return response()->json([
                'message' => 'Failed to update relation.',
            ], 500);
        }

        return response()->json([
            'message' => 'Relation updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $this->relationInterface->deleteById($id);
        return response()->json();
    }

    public function show($id)
    {
        $relation = $this->relationInterface->findById($id);
        return response()->json($relation, 200);
    }

}
