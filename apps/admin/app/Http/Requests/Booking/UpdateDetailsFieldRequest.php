<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailsFieldRequest extends FormRequest
{
    public function rules()
    {
        return [
            'field' => ['required', 'string'],
            'value' => ['required']
        ];
    }

    public function getField(): string
    {
        return $this->post('field');
    }

    public function getValue(): mixed
    {
        return $this->post('value');
    }
}
