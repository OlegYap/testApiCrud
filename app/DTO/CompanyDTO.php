<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class CompanyDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly ?UploadedFile $logo = null
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name,
            description: $request->description,
            logo: $request->file('logo')
        );
    }
}
