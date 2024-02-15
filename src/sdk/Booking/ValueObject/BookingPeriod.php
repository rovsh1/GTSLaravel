<?php

namespace Sdk\Booking\ValueObject;

use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Sdk\Module\Support\DateTimeImmutable;
use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class BookingPeriod implements CanEquate, SerializableInterface
{
    private int $daysCount;

    public function __construct(
        private readonly DateTimeImmutable $dateFrom,
        private readonly DateTimeImmutable $dateTo,
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
            DateTimeImmutableFactory::createFromTimestamp($period->getStartDate()->getTimestamp()),
            DateTimeImmutableFactory::createFromTimestamp($period->getEndDate()->getTimestamp()),
        );
    }

    public static function createFromInterface(\DateTimeInterface $dateFrom, \DateTimeInterface $dateTo): static
    {
        return new static(
            DateTimeImmutableFactory::createFromTimestamp($dateFrom->getTimestamp()),
            DateTimeImmutableFactory::createFromTimestamp($dateTo->getTimestamp()),
        );
    }

    public function dateFrom(): DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function dateTo(): DateTimeImmutable
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

    public function serialize(): array
    {
        return [
            'dateFrom' => $this->dateFrom->getTimestamp(),
            'dateTo' => $this->dateTo->getTimestamp(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new BookingPeriod(
            DateTimeImmutableFactory::createFromTimestamp($payload['dateFrom']),
            DateTimeImmutableFactory::createFromTimestamp($payload['dateTo']),
        );
    }
}
