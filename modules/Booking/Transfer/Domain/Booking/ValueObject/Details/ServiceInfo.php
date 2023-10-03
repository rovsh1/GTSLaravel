<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Domain\Booking\ValueObject\Details;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Booking\TransferServiceTypeEnum;

class ServiceInfo implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly TransferServiceTypeEnum $type,
        private readonly int $supplierId,
        private readonly int $cityId,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): TransferServiceTypeEnum
    {
        return $this->type;
    }

    public function supplierId(): int
    {
        return $this->supplierId;
    }

    public function cityId(): int
    {
        return $this->cityId;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
            'supplierId' => $this->supplierId,
            'cityId' => $this->cityId
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
            TransferServiceTypeEnum::from($data['type']),
            $data['supplierId']
        );
    }
}
