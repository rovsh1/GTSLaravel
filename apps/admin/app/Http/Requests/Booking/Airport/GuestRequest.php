<?php

namespace App\Admin\Http\Requests\Booking\Airport;

use Illuminate\Foundation\Http\FormRequest;

class GuestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'guest_id' => ['required', 'numeric'],
        ];
    }

    public function getGuestId(): int
    {
        return $this->post('guest_id');
    }
}
