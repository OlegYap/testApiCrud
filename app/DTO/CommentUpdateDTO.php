<?php

namespace App\DTO;

class CommentUpdateDTO
{
    public function __construct(
        public readonly ?string $content = null,
        public readonly ?int $rating = null
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'content' => $this->content,
            'rating' => $this->rating,
        ], fn($value) => !is_null($value));
    }

    public static function fromRequest($request): self
    {
        return new self(
            content: $request->content,
            rating: $request->rating
        );
    }
}
