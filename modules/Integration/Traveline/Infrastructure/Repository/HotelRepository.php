<?php

namespace Module\Integration\Traveline\Infrastructure\Repository;

use Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;
use Module\Integration\Traveline\Infrastructure\Models\TravelineHotel;

class HotelRepository implements HotelRepositoryInterface
{
    public function isHotelIntegrationEnabled(int $hotelId): bool
    {
        return TravelineHotel::whereHotelId($hotelId)->exists();
    }

    public function getIntegratedHotelIds(): array
    {
        return TravelineHotel::all()->pluck('hotel_id')->toArray();
    }
}
