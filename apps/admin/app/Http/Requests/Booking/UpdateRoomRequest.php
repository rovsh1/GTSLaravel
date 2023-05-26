<?php

namespace App\Admin\Http\Requests\Booking;

class UpdateRoomRequest extends AddRoomRequest
{
    public function rules()
    {
        return [
            'room_index' => ['required', 'numeric'],
            ...parent::rules(),
        ];
    }

    public function getRoomIndex(): int
    {
        return $this->post('room_index');
    }
}
