<?php

namespace App\Admin\Http\Requests\Booking\Room;

class DeleteRoomGuestRequest extends AddRoomGuestRequest
{
    public function rules()
    {
        return [
            'guest_index' => ['required', 'numeric'],
            'room_booking_id' => ['required', 'numeric'],
        ];
    }

    public function getGuestIndex(): int
    {
        return $this->post('guest_index');
    }
}
