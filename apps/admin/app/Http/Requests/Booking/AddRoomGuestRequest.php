<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class AddRoomGuestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'full_name' => ['required', 'string'],
            'nationality_id' => ['required', 'numeric'],
            'gender' => ['required', 'numeric'],
            'room_index' => ['required', 'numeric'],
        ];
    }

    public function getRoomIndex(): int
    {
        return $this->post('room_index');
    }

    public function getFullName(): string
    {
        return $this->post('full_name');
    }

    public function getNationalityId(): int
    {
        return $this->post('nationality_id');
    }

    public function getGender(): int
    {
        return $this->post('gender');
    }
}
