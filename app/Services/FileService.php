<?php

namespace App\Services;

use App\DTO\FileDTO;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function upload(FileDTO $dto): File
    {
        try {
            $path = $dto->file->store('images', 'public');

            return File::create([
                'path' => $path,
                'original_name' => $dto->file->getClientOriginalName(),
                'mime_type' => $dto->file->getMimeType(),
                'size' => $dto->file->getSize()
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage());
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }

    public function delete(File $file): void
    {
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        $file->delete();
    }
}
