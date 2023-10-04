<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Booking\ValueObject\Details;

use Module\Booking\Domain\Shared\ValueObject\ServiceInfoInterface;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Booking\AirportServiceTypeEnum;

class ServiceInfo implements ValueObjectInterface, SerializableDataInterface, ServiceInfoInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly AirportServiceTypeEnum $type,
        private readonly int $supplierId,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): AirportServiceTypeEnum
    {
        return $this->type;
    }

    public function supplierId(): int
    {
        return $this->supplierId;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
            'supplierId' => $this->supplierId
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
            AirportServiceTypeEnum::from($data['type']),
            $data['supplierId'],
        );
    }
}
