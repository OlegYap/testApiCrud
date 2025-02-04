<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class CommentUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'sometimes|string|min:150|max:550',
            'rating' => 'sometimes|integer|min:1|max:10',
            'user_id' => 'sometimes|exists:users,id',
            'company_id' => 'sometimes|exists:companies,id'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => collect($validator->errors())->map(function ($errors, $field) {
                return [
                    'field' => $field,
                    'message' => $errors[0]
                ];
            })->values()->all()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
