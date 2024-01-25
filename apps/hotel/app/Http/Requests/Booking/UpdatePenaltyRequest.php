<?php

namespace App\Hotel\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenaltyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'penalty' => ['nullable', 'numeric'],
        ];
    }

    public function getPenalty(): ?float
    {
        $value = $this->post('penalty');
        if ($value === null) {
            return $value;
        }

        return (float)$value;
    }
}
