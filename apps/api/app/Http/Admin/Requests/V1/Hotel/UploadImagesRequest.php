<?php

namespace App\Api\Http\Admin\Requests\V1\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UploadImagesRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'files' => ['required', 'array'],
            'files.*' => 'mimetypes:image/*'
        ];
    }

    public function getFiles(): array
    {
        return $this->file('files');
    }
}
