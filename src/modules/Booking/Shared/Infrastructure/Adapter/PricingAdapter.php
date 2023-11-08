<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Booking\Pricing\Application\UseCase\RecalculatePrices;
use Module\Booking\Shared\Domain\Booking\Adapter\PricingAdapterInterface;

class PricingAdapter implements PricingAdapterInterface
{
    public function recalculate(int $bookingId): void
    {
        app(RecalculatePrices::class)->execute($bookingId);
    }
}
