<?php

namespace App\DTO;

class CommentDTO
{
    public function __construct(
        public readonly string $content,
        public readonly int $rating,
        public readonly int $user_id,
        public readonly int $company_id
    ) {}

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'rating' => $this->rating,
            'user_id' => $this->user_id,
            'company_id' => $this->company_id
        ];
    }

    public static function fromRequest($request): self
    {
        return new self(
            content: $request->content,
            rating: $request->rating,
            user_id: $request->user_id,
            company_id: $request->company_id
        );
    }
}
