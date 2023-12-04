<?php

namespace Sdk\Booking\ValueObject;

use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use DateTimeImmutable;
use DateTimeInterface;
use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class OrderPeriod implements CanEquate, SerializableInterface
{
    public function __construct(
        private readonly DateTimeInterface $dateFrom,
        private readonly DateTimeInterface $dateTo,
    ) {
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

    /**
     * @param self $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof OrderPeriod) {
            return false;
        }

        return $this->dateFrom->getTimestamp() === $b->dateFrom->getTimestamp()
            && $this->dateTo->getTimestamp() === $b->dateTo->getTimestamp();
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
        return new OrderPeriod(
            DateTimeImmutableFactory::createFromTimestamp($payload['dateFrom']),
            DateTimeImmutableFactory::createFromTimestamp($payload['dateTo']),
        );
    }
}
