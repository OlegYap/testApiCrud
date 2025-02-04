<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        // Ошибка валидации
        $this->renderable(function (ValidationException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => collect($e->errors())->map(function ($errors, $field) {
                    return [
                        'field' => $field,
                        'message' => $errors[0]
                    ];
                })->values()->all()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        // Ресурс не найден
        $this->renderable(function (ModelNotFoundException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Resource not found',
                'error' => [
                    'model' => class_basename($e->getModel()),
                    'id' => $e->getIds()[0] ?? null
                ]
            ], Response::HTTP_NOT_FOUND);
        });

        // Некорректный запрос
        $this->renderable(function (HttpException $e) {
            if ($e->getStatusCode() === Response::HTTP_BAD_REQUEST) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Invalid request',
                    'error' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
        });

        // Все остальные ошибки
        $this->renderable(function (Throwable $e) {
            if (request()->expectsJson()) {
                $response = [
                    'status' => 'error',
                    'message' => 'Server error'
                ];

                if (config('app.debug')) {
                    $response['error'] = [
                        'message' => $e->getMessage(),
                        'type' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ];
                }

                return new JsonResponse($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}
