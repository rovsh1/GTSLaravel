<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject;

use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Enum\SourceEnum;

class Context implements SerializableInterface
{
    public function __construct(
        private readonly SourceEnum $source,
        private readonly CreatorId $creatorId,
    ) {
    }

    public function source(): SourceEnum
    {
        return $this->source;
    }

    public function creatorId(): CreatorId
    {
        return $this->creatorId;
    }

    public function serialize(): array
    {
        return [
            'source' => $this->source->value,
            'creatorId' => $this->creatorId->value()
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new Context(
            SourceEnum::from($payload['source']),
            new CreatorId($payload['creatorId'])
        );
    }
}