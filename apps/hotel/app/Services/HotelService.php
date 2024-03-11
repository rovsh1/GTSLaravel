<?php

declare(strict_types=1);

namespace App\Hotel\Services;

use App\Hotel\Models\Hotel;

class HotelService
{
    private Hotel $hotel;

    public function setHotel(int $hotelId): void
    {
        $this->hotel = Hotel::find($hotelId);
    }

    public function getHotel(): Hotel
    {
        if (!isset($this->hotel)) {
            throw new \RuntimeException('Undefined hotel');
        }

        return $this->hotel;
    }

    public function getHotelId(): int
    {
        return $this->hotel->id;
    }
}
