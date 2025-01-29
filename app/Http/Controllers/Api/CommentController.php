<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use App\DTO\CommentDTO;
use Illuminate\Http\JsonResponse;
class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $comments = $this->commentService->getPaginated();
            return response()->json($comments);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(CommentRequest $request): JsonResponse
    {
        try {
            $dto = CommentDTO::fromRequest($request);
            $comment = $this->commentService->create($dto);
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Comment $comment): JsonResponse
    {
        try {
            return response()->json($comment->load(['user', 'company']));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        try {
            $dto = CommentDTO::fromRequest($request);
            $updatedComment = $this->commentService->update($comment, $dto);
            return response()->json($updatedComment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Comment $comment): JsonResponse
    {
        try {
            $this->commentService->delete($comment);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
