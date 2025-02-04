<?php

namespace App\Services;

use App\DTO\CommentUpdateDTO;
use App\Models\Comment;
use App\DTO\CommentDTO;
use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Comment::with(['user', 'company'])->paginate($perPage);
    }

    public function create(CommentDTO $dto): Comment
    {
        try {
            $company = Company::findOrFail($dto->company_id);

            $comment = new Comment($dto->toArray());
            $comment->user_id = $dto->user_id;

            $comment->commentable()->associate($company);
            $comment->save();

            return $comment->load(['user', 'commentable']);
        } catch (\Exception $e) {
            Log::error('Error creating comment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Comment $comment, CommentUpdateDTO $dto): Comment
    {
        try {
            $comment->update($dto->toArray());
            return $comment->load(['user', 'company']);
        } catch (\Exception $e) {
            Log::error('Error updating comment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Comment $comment): void
    {
        try {
            $comment->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            throw $e;
        }
    }
}
