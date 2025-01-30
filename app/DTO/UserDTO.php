<?php

namespace App\DTO;
use Illuminate\Http\UploadedFile;

class UserDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $phone,
        public readonly ?int $avatar_id
    ) {}

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'avatar_id' => $this->avatar_id,
        ];
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
