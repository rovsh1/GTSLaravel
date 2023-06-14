<?php

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class BookingPeriod implements ValueObjectInterface, SerializableDataInterface
{
    private int $nightsCount;

    public function __construct(
        private ?CarbonImmutable $dateFrom,
        private ?CarbonImmutable $dateTo,
    ) {
        $calculatedNightsCount = CarbonPeriod::create($dateFrom, $dateTo, 'P1D')
            ->excludeEndDate()
            ->count();
        $this->nightsCount = $calculatedNightsCount;
    }

    public function dateFrom(): ?CarbonImmutable
    {
        return $this->dateFrom;
    }

    public function dateTo(): ?CarbonImmutable
    {
        return $this->dateTo;
    }

    public function nightsCount(): int
    {
        return $this->nightsCount;
    }

    public function toData(): array
    {
        return [
            'dateFrom' => $this->dateFrom->getTimestamp(),
            'dateTo' => $this->dateTo->getTimestamp(),
            'nightsCount' => $this->nightsCount
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            CarbonImmutable::createFromFormat('U', $data['dateFrom']),
            CarbonImmutable::createFromFormat('U', $data['dateTo']),
        );
    }

    public static function fromCarbon(CarbonPeriod|CarbonPeriodImmutable $period): static
    {
        return new static(
            $period->getStartDate()->toImmutable(),
            $period->getEndDate()->toImmutable(),
        );
    }
}
