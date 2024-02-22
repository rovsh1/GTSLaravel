<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\HotelBooking;

use Sdk\Shared\Contracts\Support\SerializableInterface;

final class ExternalNumber implements SerializableInterface
{
    public function __construct(
        private readonly ExternalNumberTypeEnum $type,
        private readonly ?string $number
    ) {}

    public function type(): ExternalNumberTypeEnum
    {
        return $this->type;
    }

    public function number(): ?string
    {
        return $this->number;
    }

    public function serialize(): array
    {
        return [
            'type' => $this->type->value,
            'number' => $this->number
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            ExternalNumberTypeEnum::from($payload['type']),
            $payload['number'],
        );
    }
}
