<?php

namespace App\Admin\Http\Requests\Booking;

class DeleteRoomRequest extends AddRoomRequest
{
    public function rules()
    {
        return [
            'room_index' => ['required', 'numeric'],
        ];
    }

    public function getRoomIndex(): int
    {
        return $this->post('room_index');
    }
}
