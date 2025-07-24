<?php

namespace App\Http\Controllers\UserType;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserType\UserTypeCreateRequest;
use App\Repositories\All\UserType\UserTypeInterface;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    protected $userTypeInterface;

    public function __construct(UserTypeInterface $userTypeInterface)
    {
        $this->userTypeInterface = $userTypeInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userTypes = $this->userTypeInterface->all();
        return response()->json($userTypes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserTypeCreateRequest $request)
    {
        $validatedType = $request->validated();

        $this->userTypeInterface->create($validatedType);

        return response()->json([
            'message' => 'User Type Created successfully!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userType = $this->userTypeInterface->findById($id);
        return response()->json($userType, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserTypeCreateRequest $request, string $id)
    {
        $userType = $this->userTypeInterface->findById($id);

        if (!$userType) {
            return response()->json([
                'message' => 'User Type not found!',
            ], 404);
        }

        $validatedUserType = $request->validated();

        $updatedUserType = $this->userTypeInterface->update($id, $validatedUserType);

        if (!$updatedUserType) {
            return response()->json([
                'message' => 'Failed to update User Type.',
            ], 500);
        }

        return response()->json([
            'message' => 'User Type updated successfully!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userTypeInterface->deleteById($id);
        return response()->json();
    }
}
