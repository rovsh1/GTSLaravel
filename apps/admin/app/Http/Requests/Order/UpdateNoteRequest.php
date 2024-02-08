<?php

namespace App\Admin\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'note' => ['nullable', 'string'],
        ];
    }

    public function getNote(): ?string
    {
        return $this->post('note');
    }
}
