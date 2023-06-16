<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\ValueObject\Details;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Booking\ServiceTypeEnum;

class ServiceInfo implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly ServiceTypeEnum $type
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): ServiceTypeEnum
    {
        return $this->type;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
            ServiceTypeEnum::from($data['type']),
        );
    }
}
