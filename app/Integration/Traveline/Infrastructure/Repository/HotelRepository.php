<?php

namespace GTS\Integration\Traveline\Infrastructure\Repository;

use GTS\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;
use GTS\Integration\Traveline\Infrastructure\Models\TravelineHotel;

class HotelRepository implements HotelRepositoryInterface
{
    public function isHotelIntegrationEnabled(int $hotelId): bool
    {
        return TravelineHotel::whereHotelId($hotelId)->exists();
    }
}
