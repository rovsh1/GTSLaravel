<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject;

use Module\Shared\Contracts\Support\SerializableInterface;

final class ServiceInfo implements SerializableInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly int $supplierId,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function supplierId(): int
    {
        return $this->supplierId;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'supplierId' => $this->supplierId,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new ServiceInfo(
            $payload['id'],
            $payload['title'],
            $payload['supplierId'],
        );
    }
}
