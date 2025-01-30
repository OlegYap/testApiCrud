<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|min:3|max:40',
            'last_name' => 'sometimes|string|min:3|max:40',
            'phone' => 'sometimes|string|regex:/^\+7\d{10}$/',
            'avatar' => 'sometimes|image|mimes:jpg,png|max:2048'
        ];
    }
}
