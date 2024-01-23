<?php

namespace App\Hotel\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'numeric'],
            'not_confirmed_reason' => ['nullable', 'string'],
        ];
    }

    public function getStatus(): int
    {
        return $this->post('status');
    }

    public function getNotConfirmedReason(): ?string
    {
        return $this->post('not_confirmed_reason');
    }
}
