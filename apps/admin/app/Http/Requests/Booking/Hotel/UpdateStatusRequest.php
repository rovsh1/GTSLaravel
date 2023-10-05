<?php

namespace App\Admin\Http\Requests\Booking\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'numeric'],
            'not_confirmed_reason' => ['nullable', 'string'],
            'net_penalty' => ['nullable', 'numeric'],
            'gross_penalty' => ['nullable', 'numeric'],
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

    public function getNetPenalty(): ?string
    {
        return $this->post('net_penalty');
    }

    public function getGrossPenalty(): ?string
    {
        return $this->post('gross_penalty');
    }
}
