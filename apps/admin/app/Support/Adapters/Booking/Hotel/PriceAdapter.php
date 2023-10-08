<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Module\Booking\Application\Admin\HotelBooking\UseCase\Room\SetManualPrice;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Price\SetCalculatedGrossPrice;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Price\SetCalculatedNetPrice;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Price\SetGrossPenalty;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Price\SetManualGrossPrice;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Price\SetManualNetPrice;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Price\SetNetPenalty;

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
