<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function rules()
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
