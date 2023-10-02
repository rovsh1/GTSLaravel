<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetCalculatedGrossPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetCalculatedNetPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetGrossPenalty;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetManualGrossPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetManualNetPrice;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Price\SetNetPenalty;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Room\SetManualPrice;

class PriceAdapter
{
    public function setGrossPrice(int $bookingId, float $price): void
    {
        app(SetManualGrossPrice::class)->execute($bookingId, $price);
    }

    public function setNetPrice(int $bookingId, float $price): void
    {
        app(SetManualNetPrice::class)->execute($bookingId, $price);
    }

    public function setCalculatedGrossPrice(int $bookingId): void
    {
        app(SetCalculatedGrossPrice::class)->execute($bookingId);
    }

    public function setCalculatedNetPrice(int $bookingId): void
    {
        app(SetCalculatedNetPrice::class)->execute($bookingId);
    }

    public function updateRoomPrice(int $bookingId, int $roomBookingId, float|null $grossPrice, float|null $netPrice): void
    {
        app(SetManualPrice::class)->execute($bookingId, $roomBookingId, $grossPrice, $netPrice);
    }

    public function setNetPenalty(int $bookingId, float|null $penalty): void
    {
        app(SetNetPenalty::class)->execute($bookingId, $penalty);
    }

    public function setGrossPenalty(int $bookingId, float|null $penalty): void
    {
        app(SetGrossPenalty::class)->execute($bookingId, $penalty);
    }
}
