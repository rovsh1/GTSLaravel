<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetBoPenalty;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetCalculatedBoPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetCalculatedHoPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetHoPenalty;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetManualBoPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetManualHoPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Room\SetManualPrice;

class HotelPriceAdapter
{
    public function setBoPrice(int $bookingId, float $price): void
    {
        app(SetManualBoPrice::class)->execute($bookingId, $price);
    }

    public function setHoPrice(int $bookingId, float $price): void
    {
        app(SetManualHoPrice::class)->execute($bookingId, $price);
    }

    public function setCalculatedBoPrice(int $bookingId): void
    {
        app(SetCalculatedBoPrice::class)->execute($bookingId);
    }

    public function setCalculatedHoPrice(int $bookingId): void
    {
        app(SetCalculatedHoPrice::class)->execute($bookingId);
    }

    public function updateRoomPrice(int $bookingId, int $roomBookingId, float|null $boPrice, float|null $hoPrice): void
    {
        app(SetManualPrice::class)->execute($bookingId, $roomBookingId, $boPrice, $hoPrice);
    }

    public function setHoPenalty(int $bookingId, float|null $penalty): void
    {
        app(SetHoPenalty::class)->execute($bookingId, $penalty);
    }

    public function setBoPenalty(int $bookingId, float|null $penalty): void
    {
        app(SetBoPenalty::class)->execute($bookingId, $penalty);
    }
}
