<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-media') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $extensions = [...config('cms.uploads.image_mimes'), ...config('cms.uploads.document_mimes')];

        return [
            'files' => ['required', 'array', 'max:10'],
            'files.*' => [
                'required',
                File::types($extensions)->max((int) config('cms.uploads.max_kb')),
                'extensions:'.implode(',', $extensions),
            ],
            'collection' => ['nullable', 'string', 'max:50', 'alpha_dash'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['files.*' => 'dosya'];
    }
}
