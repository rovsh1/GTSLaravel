<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Booking\ValueObject\Details;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

class AdditionalInfo implements SerializableDataInterface
{
    public function __construct(
        private readonly string $flightNumber
    ) {}

    public function flightNumber(): string
    {
        return $this->flightNumber;
    }

    public function toData(): array
    {
        return [
            'flightNumber' => $this->flightNumber
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['flightNumber']
        );
    }
}
