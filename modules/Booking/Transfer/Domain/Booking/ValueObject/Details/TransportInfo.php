<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Domain\Booking\ValueObject\Details;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class TransportInfo implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
        );
    }
}
