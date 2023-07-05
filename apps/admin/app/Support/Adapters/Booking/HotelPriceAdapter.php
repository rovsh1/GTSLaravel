<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Hotel\Application\UseCase\Admin\SetManualPrice;

class HotelPriceAdapter
{
    public function updatePrice(int $bookingId, float|null $boPrice, float|null $hoPrice): void
    {
        app(SetManualPrice::class)->execute($bookingId, $boPrice, $hoPrice);
    }

    public function updateRoomPrice(int $bookingId, int $roomBookingId, float|null $boPrice, float|null $hoPrice): void
    {
        app(Room\SetManualPrice::class)->execute($bookingId, $roomBookingId, $boPrice, $hoPrice);
    }
}
