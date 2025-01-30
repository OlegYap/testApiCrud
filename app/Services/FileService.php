<?php

namespace App\Services;

use App\DTO\FileDTO;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function upload(FileDTO $dto): File
    {
        $path = $dto->file->store('avatars', 'public');

        return File::create([
            'path' => $path,
            'original_name' => $dto->file->getClientOriginalName(),
            'mime_type' => $dto->file->getMimeType(),
            'size' => $dto->file->getSize()
        ]);
    }

    public function delete(File $file): void
    {
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();
    }
}
