<?php

namespace App\Admin\Http\Requests\Booking\Room;

use Illuminate\Foundation\Http\FormRequest;

class AddRoomGuestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'full_name' => ['required', 'string'],
            'country_id' => ['required', 'numeric'],
            'gender' => ['required', 'numeric'],
            'room_booking_id' => ['required', 'numeric'],
            'is_adult' => ['required', 'bool'],
            'age' => ['required_if:is_adult,false', 'numeric', 'nullable']
        ];
    }

    public function getRoomBookingId(): int
    {
        return $this->post('room_booking_id');
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

    public function getAge(): ?int
    {
        return $this->post('age');
    }
}