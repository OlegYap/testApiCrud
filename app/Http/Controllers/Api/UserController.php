<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
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
        $users = $this->userService->getPaginated();
        return response()->json($users);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $dto = UserDTO::fromRequest($request);
        $user = $this->userService->create($dto);
        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $dto = UserUpdateDTO::fromRequest($request);
        $updatedUser = $this->userService->update($user, $dto);
        return response()->json($updatedUser);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);
        return response()->json(null, 204);
    }
}
