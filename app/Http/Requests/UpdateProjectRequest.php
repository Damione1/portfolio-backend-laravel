<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'excerpt' => 'sometimes|string|max:500',
            'status' => 'sometimes|in:published,draft,archived',
            'cover_image_id' => 'sometimes|exists:images,id',
            'skills' => 'sometimes|array|exists:skills,id',
        ];
    }
}
