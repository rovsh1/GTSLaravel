<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'boPrice' => ['nullable', 'numeric'],
            'hoPrice' => ['nullable', 'numeric'],
        ];
    }

    public function getRoomBookingId(): int
    {
        return (int)$this->post('room_booking_id');
    }

    public function getBoPrice(): ?float
    {
        if ($this->has('boPrice')) {
            return (float)$this->post('boPrice');
        }

        return null;
    }

    public function getHoPrice(): ?float
    {
        if ($this->has('hoPrice')) {
            return (float)$this->post('hoPrice');
        }

        return null;
    }
}
