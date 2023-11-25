<?php

declare(strict_types=1);

namespace Sdk\Shared\ValueObject;

use DateTimeImmutable;
use Module\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Support\DateTimeImmutableFactory;

final class Timestamps implements SerializableInterface
{
    public function __construct(
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt,
    ) {
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function serialize(): array
    {
        return [
            'createdAt' => $this->createdAt->getTimestamp(),
            'updatedAt' => $this->updatedAt->getTimestamp()
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new Timestamps(
            DateTimeImmutableFactory::createFromTimestamp($payload['createdAt']),
            DateTimeImmutableFactory::createFromTimestamp($payload['updatedAt']),
        );
    }
}
