<?php

namespace App\Services;
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
            if (!$dto->avatar instanceof UploadedFile) {
                throw ValidationException::withMessages([
                    'avatar' => ['The avatar file is required.']
                ]);
            }

            $userData = $dto->toArray();
            $avatarPath = $this->uploadAvatar($dto->avatar);

            if (!$avatarPath) {
                throw ValidationException::withMessages([
                    'avatar' => ['Failed to upload avatar.']
                ]);
            }

            $userData['avatar'] = $avatarPath;
            return User::create($userData);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());

            // Если файл был загружен, но создание пользователя не удалось,
            // удаляем загруженный файл
            if (isset($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            throw $e;
        }
    }

    public function update(User $user, UserDTO $dto): User
    {
        try {
            $userData = $dto->toArray();

            if ($dto->avatar instanceof UploadedFile) {
                $avatarPath = $this->uploadAvatar($dto->avatar);

                if (!$avatarPath) {
                    throw ValidationException::withMessages([
                        'avatar' => ['Failed to upload avatar.']
                    ]);
                }

                $this->deleteOldAvatar($user);
                $userData['avatar'] = $avatarPath;
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
            $this->deleteOldAvatar($user);
            $user->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            throw $e;
        }
    }

    private function uploadAvatar(UploadedFile $file): ?string
    {
        try {
            return $file->store('avatars', 'public');
        } catch (\Exception $e) {
            Log::error('Error uploading avatar: ' . $e->getMessage());
            return null;
        }
    }

    private function deleteOldAvatar(User $user): void
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
    }
}
