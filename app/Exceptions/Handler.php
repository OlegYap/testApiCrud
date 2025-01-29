<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        });

        $this->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found.',
            ], 404);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'The requested resource was not found.',
            ], 404);
        });

        $this->renderable(function (Throwable $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'An error occurred while processing your request.',
                ], 500);
            }
        });
    }
}
