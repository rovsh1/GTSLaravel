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
        return $this->integer('age', null);
    }

    public function hotelBookingId(): ?int
    {
        return $this->integer('hotelBookingId', null);
    }

    public function hotelBookingRoomId(): ?int
    {
        return $this->integer('hotelBookingRoomId', null);
    }

    public function airportBookingId(): ?int
    {
        return $this->integer('airportBookingId', null);
    }

    public function carBidBookingId(): ?int
    {
        return $this->integer('carBidBookingId', null);
    }

    public function carBidId(): ?int
    {
        return $this->integer('carBidId', null);
    }
}
