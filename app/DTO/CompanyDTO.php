<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class CompanyDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly int $logo_id
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'logo_id' => $this->logo_id,
        ];
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name,
            description: $request->description,
            logo_id: $request->logo_id
        );
    }
}
