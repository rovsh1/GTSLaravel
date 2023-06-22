<?php

namespace App\Admin\Http\Requests\Booking;

class DeleteRoomGuestRequest extends AddRoomGuestRequest
{
    public function rules()
    {
        return [
            'guest_index' => ['required', 'numeric'],
            'room_index' => ['required', 'numeric'],
        ];
    }

    public function getRoomIndex(): int
    {
        return $this->post('room_index');
    }

    public function getGuestIndex(): int
    {
        return $this->post('guest_index');
    }
}
