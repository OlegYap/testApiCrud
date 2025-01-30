<?php

namespace App\Services;
use App\Models\File;
use App\Models\User;
use App\DTO\UserDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function create(UserDTO $dto): User
    {
        try {
            if (!File::find($dto->avatar_id)) {
                throw ValidationException::withMessages([
                    'avatar_id' => ['The selected avatar file does not exist.']
                ]);
            }
            return User::create($dto->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(User $user, UserDTO $dto): User
    {
        try {
            $userData = $dto->toArray();

            if (isset($userData['avatar_id']) && $userData['avatar_id'] !== $user->avatar_id) {
                // Проверяем существование нового файла
                if (!File::find($userData['avatar_id'])) {
                    throw ValidationException::withMessages([
                        'avatar_id' => ['The selected avatar file does not exist.']
                    ]);
                }
            }

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
