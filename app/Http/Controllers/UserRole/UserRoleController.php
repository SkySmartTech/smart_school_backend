<?php

namespace App\Http\Controllers\UserRole;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRole\UserRoleCreateRequest;
use App\Repositories\All\UserRole\UserRoleInterface;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    protected $userRoleInterface;

    public function __construct(UserRoleInterface $userRoleInterface)
    {
        $this->userRoleInterface = $userRoleInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userRoles = $this->userRoleInterface->all();
        return response()->json($userRoles, 200);
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
    public function store(UserRoleCreateRequest $request)
    {
        $validatedRole = $request->validated();

        $this->userRoleInterface->create($validatedRole);

        return response()->json([
            'message' => 'User Role Created successfully!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userRole = $this->userRoleInterface->findById($id);
        return response()->json($userRole, 200);
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
    public function update(UserRoleCreateRequest $request, string $id)
    {
        $userRole = $this->userRoleInterface->findById($id);

        if (!$userRole) {
            return response()->json([
                'message' => 'User Role not found!',
            ], 404);
        }

        $validatedUserRole = $request->validated();

        $updatedUserRole = $this->userRoleInterface->update($id, $validatedUserRole);

        if (!$updatedUserRole) {
            return response()->json([
                'message' => 'Failed to update User Role.',
            ], 500);
        }

        return response()->json([
            'message' => 'User Role updated successfully!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userRoleInterface->deleteById($id);
        return response()->json();
    }
}
