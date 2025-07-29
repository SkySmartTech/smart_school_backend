<?php
namespace App\Http\Controllers\UserAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAccess\UserAccessCreateRequest;
use App\Repositories\All\UserAccess\UserAccessInterface;

class UserAccessController extends Controller
{
    protected $userAccessInterface;

    public function __construct(UserAccessInterface $userAccessInterface)
    {
        $this->userAccessInterface = $userAccessInterface;
    }

    public function index()
    {
        $userAccesses = $this->userAccessInterface->all();
        return response()->json($userAccesses, 200);
    }

    public function store(UserAccessCreateRequest $request)
    {
        $validated = $request->validated();

        $this->userAccessInterface->create($validated);

        return response()->json([
            'message' => 'User Access created successfully!',
        ], 201);
    }

    public function show($id)
    {
        $userAccess = $this->userAccessInterface->findById($id);
        return response()->json($userAccess, 200);
    }

    public function update(UserAccessCreateRequest $request, $id)
    {
        $data = $request->validated();

        $updatedUserAccess = $this->userAccessInterface->update($id, $data);

        return response()->json([
            'message' => 'User Access updated successfully!',
        ]);
    }

    public function destroy($id)
    {
        $this->userAccessInterface->deleteById($id);
        return response()->json();
    }
}
