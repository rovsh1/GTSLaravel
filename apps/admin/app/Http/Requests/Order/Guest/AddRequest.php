<?php

namespace App\Admin\Http\Requests\Order\Guest;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
{
    public function rules()
    {
        return [
            'full_name' => ['required', 'string'],
            'country_id' => ['required', 'numeric'],
            'gender' => ['required', 'numeric'],
            'is_adult' => ['required', 'bool'],
            'age' => ['required_if:is_adult,false', 'numeric', 'nullable'],

            'hotelBookingId' => ['nullable', 'numeric'],
            'hotelBookingRoomId' => ['required_with:hotelBookingId', 'numeric'],
            'airportBookingId' => ['nullable', 'numeric'],

            'carBidBookingId' => ['nullable', 'numeric'],
            'carBidId' => ['required_with:carBidBookingId', 'numeric'],
        ];
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

    public function hotelBookingId(): ?int
    {
        return $this->post('hotelBookingId');
    }

    public function hotelBookingRoomId(): ?int
    {
        return $this->post('hotelBookingRoomId');
    }

    public function airportBookingId(): ?int
    {
        return $this->post('airportBookingId');
    }

    public function carBidBookingId(): ?int
    {
        return $this->post('carBidBookingId');
    }

    public function carBidId(): ?int
    {
        return $this->post('carBidId');
    }
}
