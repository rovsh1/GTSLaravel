<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Adapter;

interface PricingAdapterInterface
{
    public function recalculate(int $bookingId): void;
}
