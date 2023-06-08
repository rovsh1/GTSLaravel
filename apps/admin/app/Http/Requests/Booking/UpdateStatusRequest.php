<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'numeric'],
            'not_confirmed_reason' => ['nullable', 'string'],
            'cancel_fee_amount' => ['nullable', 'numeric'],
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

    public function getCancelFeeAmount(): ?string
    {
        return $this->post('cancel_fee_amount');
    }
}
