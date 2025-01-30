<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class FileDTO
{
    public function __construct(
        public readonly UploadedFile $file
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            file: $request->file('file')
        );
    }
}
