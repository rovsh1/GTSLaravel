<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\ValueObject\Details\AdditionalInfo;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class ExternalNumber implements ValueObjectInterface, SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'type' => $this->type->value,
            'number' => $this->number
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            ExternalNumberTypeEnum::from($data['type']),
            $data['number'],
        );
    }
}
