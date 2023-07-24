<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\Date;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class RoomDayPrice implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Date $date,
        private readonly int|float $netValue,
        private readonly int|float $boValue,
        private readonly int|float $hoValue,
        private readonly string $boFormula,
        private readonly string $hoFormula
    ) {
    }

    public function date(): Date
    {
        return $this->date;
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function hoValue(): float
    {
        return $this->hoValue;
    }

    public function boValue(): float
    {
        return $this->boValue;
    }

    public function boFormula(): string
    {
        return $this->boFormula;
    }

    public function hoFormula(): string
    {
        return $this->hoFormula;
    }

    public function toData(): array
    {
        return [
            'date' => $this->date->getTimestamp(),
            'netValue' => $this->netValue,
            'boValue' => $this->boValue,
            'hoValue' => $this->hoValue,
            'boFormula' => $this->boFormula,
            'hoFormula' => $this->hoFormula,
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomDayPrice(
            Date::createFromTimestamp($data['date']),
            $data['netValue'],
            $data['boValue'],
            $data['hoValue'],
            $data['boFormula'],
            $data['hoFormula']
        );
    }
}
