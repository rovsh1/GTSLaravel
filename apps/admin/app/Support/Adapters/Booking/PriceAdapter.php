<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Application\UseCase\Admin\Price\RecalculatePrices;
use Module\Booking\Application\UseCase\Admin\Price\SetClientPenalty;
use Module\Booking\Application\UseCase\Admin\Price\SetManualClientPrice;
use Module\Booking\Application\UseCase\Admin\Price\SetManualSupplierPrice;
use Module\Booking\Application\UseCase\Admin\Price\SetSupplierPenalty;

class PriceAdapter
{
    public function recalculatePrices(int $bookingId): void
    {
        app(RecalculatePrices::class)->execute($bookingId);
    }

    public function setManualClientPrice(int $bookingId, ?float $price): void
    {
        app(SetManualClientPrice::class)->execute($bookingId, $price);
    }

    public function setManualSupplierPrice(int $bookingId, ?float $price): void
    {
        app(SetManualSupplierPrice::class)->execute($bookingId, $price);
    }

    public function setSupplierPenalty(int $bookingId, float|null $penalty): void
    {
        app(SetSupplierPenalty::class)->execute($bookingId, $penalty);
    }

    public function setClientPenalty(int $bookingId, float|null $penalty): void
    {
        app(SetClientPenalty::class)->execute($bookingId, $penalty);
    }
}
