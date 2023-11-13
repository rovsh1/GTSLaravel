<?php

declare(strict_types=1);

namespace Module\Shared\ValueObject;

use DateTimeImmutable;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Support\DateTimeImmutableFactory;

final class Timestamps implements SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'createdAt' => $this->createdAt->getTimestamp(),
            'updatedAt' => $this->updatedAt->getTimestamp()
        ];
    }

    public static function fromData(array $data): static
    {
        return new Timestamps(
            DateTimeImmutableFactory::createFromTimestamp($data['createdAt']),
            DateTimeImmutableFactory::createFromTimestamp($data['updatedAt']),
        );
    }
}
