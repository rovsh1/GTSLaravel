<?php

namespace App\Admin\Http\Requests\Booking;

class UpdateRoomGuestRequest extends AddRoomGuestRequest
{
    public function rules()
    {
        return [
            'guest_index' => ['required', 'numeric'],
            ...parent::rules(),
        ];
    }

    public function getGuestIndex(): int
    {
        return $this->post('guest_index');
    }
}
