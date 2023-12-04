<?php

namespace Supplier\Traveline\Infrastructure\Repository;

use Supplier\Traveline\Domain\Repository\HotelRepositoryInterface;
use Supplier\Traveline\Infrastructure\Models\TravelineHotel;

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
