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
        $comments = $this->commentService->getPaginated();
        return response()->json($comments);
    }

    public function store(CommentRequest $request): JsonResponse
    {
        $dto = CommentDTO::fromRequest($request);
        $comment = $this->commentService->create($dto);
        return response()->json($comment, 201);
    }

    public function show(Comment $comment): JsonResponse
    {
        return response()->json($comment->load(['user', 'company']));
    }

    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        $dto = CommentDTO::fromRequest($request);
        $updatedComment = $this->commentService->update($comment, $dto);
        return response()->json($updatedComment);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->commentService->delete($comment);
        return response()->json(null, 204);
    }
}
