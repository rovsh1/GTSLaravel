<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Booking\Room;

use App\Admin\Http\Requests\Booking\UpdatePriceRequest as Base;

class UpdatePriceRequest extends Base
{
    public function rules(): array
    {
        return [
            'room_booking_id' => ['required', 'numeric'],
            ...parent::rules(),
        ];
    }

    public function getRoomBookingId(): int
    {
        return (int)$this->post('room_booking_id');
    }
}
