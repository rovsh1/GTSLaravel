<?php

namespace Module\Booking\Shared\Domain\Booking\ValueObject;

use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use DateTimeImmutable;
use DateTimeInterface;
use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Support\SerializableInterface;
use Module\Shared\Support\DateTimeImmutableFactory;

final class BookingPeriod implements CanEquate, SerializableInterface
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
