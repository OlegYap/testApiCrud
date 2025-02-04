<?php

namespace App\Services;
use App\DTO\UserUpdateDTO;
use App\Models\File;
use App\Models\User;
use App\DTO\UserDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function create(UserDTO $dto): User
    {
        try {
            return User::create($dto->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(User $user, UserUpdateDTO $dto): User
    {
        try {
            $userData = $dto->toArray();
            $user->update($userData);
            return $user;
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(User $user): void
    {
        try {
            $file = File::find($user->avatar_id);
            $user->delete();
            if ($file && !User::where('avatar_id', $file->id)->exists()) {
                $file->delete();
            }
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            throw $e;
        }
    }
}
