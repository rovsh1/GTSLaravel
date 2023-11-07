<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Pricing\Application\UseCase\RecalculatePrices;
use Module\Booking\Pricing\Application\UseCase\SetClientPenalty;
use Module\Booking\Pricing\Application\UseCase\SetManualClientPrice;
use Module\Booking\Pricing\Application\UseCase\SetManualSupplierPrice;
use Module\Booking\Pricing\Application\UseCase\SetSupplierPenalty;

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
