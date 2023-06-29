<?php

namespace App\Admin\Http\Requests\Booking\Room;

class UpdateRoomRequest extends AddRoomRequest
{
    public function rules()
    {
        return [
            'room_booking_id' => ['required', 'numeric'],
            ...parent::rules(),
        ];
    }

    public function getRoomBookingId(): int
    {
        return $this->post('room_booking_id');
    }
}
