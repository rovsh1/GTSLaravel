<?php

namespace Sdk\Booking\ValueObject\HotelBooking;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Sdk\Booking\Exception\BookingPeriodDatesCannotBeEqual;
use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Contracts\Support\SerializableInterface;

final class BookingPeriod implements SerializableInterface, CanEquate
{
    private readonly CarbonImmutable $dateFrom;

    private readonly CarbonImmutable $dateTo;

    private int $nightsCount;

    public function __construct(CarbonImmutable $dateFrom, CarbonImmutable $dateTo)
    {
        $this->validatePeriod($dateFrom, $dateTo);
        $calculatedNightsCount = CarbonPeriod::create($dateFrom, $dateTo, 'P1D')
            ->excludeEndDate()
            ->count();

        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
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

    private function validatePeriod(CarbonImmutable $dateFrom, CarbonImmutable $dateTo): void
    {
        if ($dateFrom->greaterThanOrEqualTo($dateTo)) {
            throw new BookingPeriodDatesCannotBeEqual();
        }
    }
}
