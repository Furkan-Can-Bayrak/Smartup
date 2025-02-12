<?php

namespace App\Http\Controllers;

use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return response()->json(['success' => true,'data'=>$users],200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'surname'     => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users,email',
                'phoneNumber'       => 'required|string|max:20',
                'companyName' => 'required|string|max:255|unique:companies,name',
            ]);

            $user = $this->userService->createUser($validated);
            return response()->json(['success' => true, 'data' => $user], 201);
        }catch (\Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->findUser($id);

            if (!$user) {
                return response()->json(['success' => false,'message' => 'User not found'], 404);
            }
            return response()->json(['success' => false,'data' =>$user],200);
        }catch (\Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name'        => 'sometimes|string|max:255',
                'surname'     => 'sometimes|string|max:255',
                'email' => 'required|string|max:255|unique:users,email,' . $id,
                'telNo'       => 'sometimes|string|max:20',
                'companyName' => 'sometimes|string|max:255|unique:companies,name'.$request->companyName,
            ]);

            $updated = $this->userService->updateUser($id, $validated);

            if (!$updated) {
                return response()->json(['success' => false,'message' => 'İşlem Başarısız'], 400);
            }

            return response()->json(['success' => true,'message' => 'User updated successfully']);

        }catch (\Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->userService->deleteUser($id);

            if (!$deleted) {
                return response()->json(['success' => false, 'message' => 'User not found or delete failed'], 400);
            }
            return response()->json(['success' => true,'message' => 'User deleted successfully']);
        }catch (\Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $filters = $request->only(['name', 'surname', 'email', 'phone', 'companyName']);
            $users = $this->userService->searchUsers($filters);

            return response()->json(['success' => true, 'message' => $users], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
