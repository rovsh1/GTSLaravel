<?php

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class PriceCalculationNotes implements SerializableDataInterface
{
    public function __construct(
        private readonly string $hoNote,
        private readonly string $boNote,
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

    public function toData(): array
    {
        return [
            'hoNote' => $this->hoNote,
            'boNote' => $this->boNote,
        ];
    }

    public static function fromData(array $data): static
    {
        return new PriceCalculationNotes(
            $data['hoNote'],
            $data['boNote'],
        );
    }
}
