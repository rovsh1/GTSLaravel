<?php

namespace App\Admin\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

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
            'files.*' => 'mimetypes:image/*',
            'room_id' => ['nullable', 'numeric']
        ];
    }

    /**
     * @return UploadedFile[]
     */
    public function getFiles(): array
    {
        return $this->file('files');
    }

    public function getRoomId(): ?int
    {
        return $this->post('room_id');
    }
}
