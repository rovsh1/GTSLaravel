<?php

namespace App\Hotel\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class SetNoCheckInRequest extends FormRequest
{
    public function rules()
    {
        return [
            'net_penalty' => ['nullable', 'numeric'],
        ];
    }

    public function getSupplierPenalty(): ?string
    {
        return $this->post('net_penalty');
    }
}
