<?php

namespace App\Services;

use App\Models\Comment;
use App\DTO\CommentDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Comment::with(['user', 'company'])->paginate($perPage);
    }

    public function getByCompany(int $companyId, int $perPage = 15): LengthAwarePaginator
    {
        return Comment::where('company_id', $companyId)
            ->with('user')
            ->paginate($perPage);
    }

    public function create(CommentDTO $dto): Comment
    {
        try {
            $comment = Comment::create($dto->toArray());

            // Установка полиморфных связей
            $comment->commentable()->associate($comment->company);
            $comment->save();

            return $comment->load(['user', 'company']);
        } catch (\Exception $e) {
            Log::error('Error creating comment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Comment $comment, CommentDTO $dto): Comment
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
