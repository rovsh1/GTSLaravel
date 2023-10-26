<?php

namespace Module\Booking\Domain\Booking\ValueObject;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Module\Shared\Contracts\CanEquate;

final class BookingPeriod implements CanEquate
{
    private int $daysCount;

    public function __construct(
        private readonly CarbonImmutable $dateFrom,
        private readonly CarbonImmutable $dateTo,
    ) {
        $calculatedDaysCount = CarbonPeriod::create($dateFrom, $dateTo, 'P1D')->count();

        $this->daysCount = $calculatedDaysCount;
    }

    public static function fromCarbon(CarbonPeriod|CarbonPeriodImmutable $period): static
    {
        return new static(
            $period->getStartDate()->toImmutable(),
            $period->getEndDate()->toImmutable(),
        );
    }

    public function dateFrom(): CarbonImmutable
    {
        return $this->dateFrom;
    }

    public function dateTo(): CarbonImmutable
    {
        return $this->dateTo;
    }

    public function daysCount(): int
    {
        return $this->daysCount;
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
            && $this->dateTo->eq($b->dateTo)
            && $this->daysCount === $b->daysCount;
    }
}
