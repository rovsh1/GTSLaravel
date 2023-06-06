<?php

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class AddRoomGuestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'full_name' => ['required', 'string'],
            'country_id' => ['required', 'numeric'],
            'gender' => ['required', 'numeric'],
            'room_index' => ['required', 'numeric'],
            'is_adult' => ['required', 'bool'],
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

    public function getCountryId(): int
    {
        return $this->post('country_id');
    }

    public function getGender(): int
    {
        return $this->post('gender');
    }

    public function getIsAdult(): bool
    {
        return $this->post('is_adult');
    }
}
