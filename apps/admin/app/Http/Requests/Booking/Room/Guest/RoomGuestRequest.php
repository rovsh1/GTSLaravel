<?php

namespace App\Admin\Http\Requests\Booking\Room\Guest;

use Illuminate\Foundation\Http\FormRequest;

class RoomGuestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'guest_id' => ['required', 'numeric'],
            'room_booking_id' => ['required', 'numeric'],
        ];
    }

    public function getRoomBookingId(): int
    {
        return $this->post('room_booking_id');
    }

    public function getGuestId(): int
    {
        return $this->post('guest_id');
    }
}
