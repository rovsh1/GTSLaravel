<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\ValueObject;

use Module\Shared\Domain\ValueObject\Date;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class RoomDayPrice implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Date $date,
        private readonly int|float $baseValue,
        private readonly int|float $grossValue,
        private readonly int|float $netValue,
        private readonly string $grossFormula,
        private readonly string $netFormula
    ) {
    }

    public function date(): Date
    {
        return $this->date;
    }

    public function baseValue(): float
    {
        return $this->baseValue;
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function grossValue(): float
    {
        return $this->grossValue;
    }

    public function grossFormula(): string
    {
        return $this->grossFormula;
    }

    public function netFormula(): string
    {
        return $this->netFormula;
    }

    public function toData(): array
    {
        return [
            'date' => $this->date->getTimestamp(),
            'baseValue' => $this->baseValue,
            'grossValue' => $this->grossValue,
            'netValue' => $this->netValue,
            'grossFormula' => $this->grossFormula,
            'netFormula' => $this->netFormula,
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomDayPrice(
            Date::createFromTimestamp($data['date']),
            $data['baseValue'],
            $data['grossValue'],
            $data['netValue'],
            $data['grossFormula'],
            $data['netFormula']
        );
    }
}
