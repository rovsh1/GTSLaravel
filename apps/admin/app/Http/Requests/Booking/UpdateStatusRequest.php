<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'numeric'],
        ];
    }

    public function getStatus(): int
    {
        return $this->post('status');
    }
}
