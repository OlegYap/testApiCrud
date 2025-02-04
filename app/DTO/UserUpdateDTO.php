<?php

namespace App\DTO;

class UserUpdateDTO
{
    public function __construct(
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $phone = null,
        public readonly ?int $avatar_id = null
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'avatar_id' => $this->avatar_id,
        ], fn($value) => !is_null($value));
    }

    public static function fromRequest($request): self
    {
        return new self(
            first_name: $request->first_name,
            last_name: $request->last_name,
            phone: $request->phone,
            avatar_id: $request->avatar_id
        );
    }
}
