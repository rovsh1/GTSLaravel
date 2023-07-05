<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomPriceDetails implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly DayPriceCollection $dayPrices,
        private readonly ?string $formulaPresentation,
    ) {}

    public function dayPrices(): DayPriceCollection
    {
        return $this->dayPrices;
    }

    public function formulaPresentation(): ?string
    {
        return $this->formulaPresentation;
    }

    public function toData(): array
    {
        return [
            'dayPrices' => $this->dayPrices->toData(),
            'formulaPresentation' => $this->formulaPresentation
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            DayPriceCollection::fromData($data['dayPrices']),
            $data['formulaPresentation']
        );
    }
}
