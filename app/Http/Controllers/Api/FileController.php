<?php

namespace App\Http\Controllers\Api;

use App\DTO\FileDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    public function __construct(
        private readonly FileService $fileService
    ) {}

    public function store(FileRequest $request): JsonResponse
    {
        $dto = FileDTO::fromRequest($request);
        $file = $this->fileService->upload($dto);
        return response()->json($file, 201);
    }
}
