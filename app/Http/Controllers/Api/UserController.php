<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\DTO\UserDTO;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->getPaginated();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {
            $dto = UserDTO::fromRequest($request);
            $user = $this->userService->create($dto);
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user): JsonResponse
    {
        try {
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        try {
            $dto = UserDTO::fromRequest($request);
            $updatedUser = $this->userService->update($user, $dto);
            return response()->json($updatedUser);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $this->userService->delete($user);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
