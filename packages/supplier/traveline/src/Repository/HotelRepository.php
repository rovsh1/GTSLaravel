<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Repository;

use Pkg\Supplier\Traveline\Models\TravelineHotel;

class HotelRepository
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
