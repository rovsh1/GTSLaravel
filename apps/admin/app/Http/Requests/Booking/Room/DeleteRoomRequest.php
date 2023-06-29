<?php

namespace App\Admin\Http\Requests\Booking\Room;

class DeleteRoomRequest extends AddRoomRequest
{
    public function rules()
    {
        return [
            'room_booking_id' => ['required', 'numeric'],
        ];
    }

    public function getRoomBookingId(): int
    {
        return $this->post('room_booking_id');
    }
}
