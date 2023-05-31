<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class RequestSendRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => ['required', 'string'],
        ];
    }

    public function getType(): string
    {
        return $this->post('type');
    }
}
