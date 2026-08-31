<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:video,text,document'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'document_file' => ['nullable', 'file', 'max:20480'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'is_free_preview' => ['nullable', 'boolean'],
        ];
    }
}
