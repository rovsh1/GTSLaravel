<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExternalNumberRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => ['required', 'numeric'],
            'number' => 'required_if:type,2',
        ];
    }

    public function getType(): int
    {
        return $this->post('type');
    }

    public function getNumber(): ?string
    {
        return $this->post('number');
    }
}
