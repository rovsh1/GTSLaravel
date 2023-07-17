<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Hotel\Application\UseCase\Admin;

class HotelPriceAdapter
{
    public function setBoPrice(int $bookingId, float $price): void
    {
        app(Admin\Price\SetManualBoPrice::class)->execute($bookingId, $price);
    }

    public function setHoPrice(int $bookingId, float $price): void
    {
        app(Admin\Price\SetManualHoPrice::class)->execute($bookingId, $price);
    }

    public function setCalculatedBoPrice(int $bookingId): void
    {
        app(Admin\Price\SetCalculatedBoPrice::class)->execute($bookingId);
    }

    public function setCalculatedHoPrice(int $bookingId): void
    {
        app(Admin\Price\SetCalculatedHoPrice::class)->execute($bookingId);
    }

    public function updateRoomPrice(int $bookingId, int $roomBookingId, float|null $boPrice, float|null $hoPrice): void
    {
        app(Admin\Room\SetManualPrice::class)->execute($bookingId, $roomBookingId, $boPrice, $hoPrice);
    }
}
