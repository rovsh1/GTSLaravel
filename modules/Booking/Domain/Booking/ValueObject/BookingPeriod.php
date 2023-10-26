<?php

namespace Module\Booking\Domain\Booking\ValueObject;

use Carbon\CarbonImmutable;
use Module\Shared\Contracts\CanEquate;

final class BookingPeriod implements CanEquate
{
    public function __construct(
        private readonly CarbonImmutable $dateFrom,
        private readonly CarbonImmutable $dateTo,
    ) {}

    public function dateFrom(): CarbonImmutable
    {
        return $this->dateFrom;
    }

    public function dateTo(): CarbonImmutable
    {
        return $this->dateTo;
    }

    /**
     * @param self $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof BookingPeriod) {
            return $b === $this;
        }

        return $this->dateFrom->eq($b->dateFrom)
            && $this->dateTo->eq($b->dateTo);
    }
}
