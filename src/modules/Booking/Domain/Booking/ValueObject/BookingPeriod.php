<?php

namespace Module\Booking\Domain\Booking\ValueObject;

use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use DateTimeInterface;
use DateTimeImmutable;
use Module\Shared\Contracts\CanEquate;

final class BookingPeriod implements CanEquate
{
    private int $daysCount;

    public function __construct(
        private readonly DateTimeInterface $dateFrom,
        private readonly DateTimeInterface $dateTo,
    ) {
        $calculatedDaysCount = CarbonPeriod::create($dateFrom, $dateTo, 'P1D')->count();
        if ($calculatedDaysCount > 1) {
            $calculatedDaysCount--;
        }

        $this->daysCount = $calculatedDaysCount;
    }

    public static function fromCarbon(CarbonPeriod|CarbonPeriodImmutable $period): static
    {
        return new static(
            DateTimeImmutable::createFromFormat('U', $period->getStartDate()->getTimestamp()),
            DateTimeImmutable::createFromFormat('U', $period->getEndDate()->getTimestamp()),
        );
    }

    public function dateFrom(): DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function dateTo(): DateTimeInterface
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

        return $this->dateFrom->getTimestamp() === $b->dateFrom->getTimestamp()
            && $this->dateTo->getTimestamp() === $b->dateTo->getTimestamp()
            && $this->daysCount === $b->daysCount;
    }
}
