<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpg,png|max:2048'
        ];
    }
}
