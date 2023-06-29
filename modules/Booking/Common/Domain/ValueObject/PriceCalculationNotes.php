<?php

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class PriceCalculationNotes implements SerializableDataInterface
{
    public function __construct(
        private readonly string $hoNote,
        private readonly string $boNote,
        private readonly float $avgDailyValue,
    ) {
    }

    public function hoNote(): string
    {
        return $this->hoNote;
    }

    public function boNote(): string
    {
        return $this->boNote;
    }

    public function avgDailyValue(): float
    {
        return $this->avgDailyValue;
    }

    public function toData(): array
    {
        return [
            'avgDailyValue' => $this->avgDailyValue,
            'hoNote' => $this->hoNote,
            'boNote' => $this->boNote,
        ];
    }

    public static function fromData(array $data): static
    {
        return new PriceCalculationNotes(
            $data['hoNote'],
            $data['boNote'],
            $data['avgDailyValue'],
        );
    }
}
