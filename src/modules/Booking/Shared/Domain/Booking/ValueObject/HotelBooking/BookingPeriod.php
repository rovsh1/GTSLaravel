<?php

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\CanEquate;
use Module\Shared\Contracts\Support\SerializableInterface;

final class BookingPeriod implements ValueObjectInterface, SerializableInterface, CanEquate
{
    private int $nightsCount;

    public function __construct(
        private readonly CarbonImmutable $dateFrom,
        private readonly CarbonImmutable $dateTo,
    ) {
        $calculatedNightsCount = CarbonPeriod::create($dateFrom, $dateTo, 'P1D')
            ->excludeEndDate()
            ->count();

        $this->nightsCount = $calculatedNightsCount;
    }

    public function dateFrom(): CarbonImmutable
    {
        return $this->dateFrom;
    }

    public function dateTo(): CarbonImmutable
    {
        return $this->dateTo;
    }

    public function nightsCount(): int
    {
        return $this->nightsCount;
    }

    /**
     * Booking dates array (without last)
     * @return array<int, CarbonInterface>
     * @throws \Exception
     */
    public function includedDates(): array
    {
        return CarbonPeriod::create($this->dateFrom, $this->dateTo, 'P1D')
            ->excludeEndDate()
            ->toArray();
    }

    /**
     * Booking dates array (with last)
     * @return CarbonInterface[]
     */
    public function dates(): array
    {
        return CarbonPeriod::create($this->dateFrom, $this->dateTo, 'P1D')
            ->toArray();
    }

    public function serialize(): array
    {
        return [
            'dateFrom' => $this->dateFrom->getTimestamp(),
            'dateTo' => $this->dateTo->getTimestamp(),
            'nightsCount' => $this->nightsCount
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            CarbonImmutable::createFromTimestamp($payload['dateFrom']),
            CarbonImmutable::createFromTimestamp($payload['dateTo']),
        );
    }

    public static function fromCarbon(CarbonPeriod|CarbonPeriodImmutable $period): static
    {
        return new static(
            $period->getStartDate()->toImmutable(),
            $period->getEndDate()->toImmutable(),
        );
    }

    /**
     * @param self $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        return $this->dateFrom->eq($b->dateFrom)
            && $this->dateTo->eq($b->dateTo)
            && $this->nightsCount === $b->nightsCount;
    }
}
