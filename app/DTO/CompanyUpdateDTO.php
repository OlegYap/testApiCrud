<?php

namespace App\DTO;

class CompanyUpdateDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?int $logo_id = null
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'logo_id' => $this->logo_id,
        ], fn($value) => !is_null($value));
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
