<?php

namespace App\DTO;
use Illuminate\Http\UploadedFile;

class UserDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $phone,
        public readonly ?UploadedFile $avatar = null
    ) {}

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
        ];
    }

    public static function fromRequest($request): self
    {
        return new self(
            first_name: $request->first_name,
            last_name: $request->last_name,
            phone: $request->phone,
            avatar: $request->file('avatar')
        );
    }
}
