<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject;

use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\SourceEnum;

class Context implements SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'source' => $this->source->value,
            'creatorId' => $this->creatorId->value()
        ];
    }

    public static function fromData(array $data): static
    {
        return new Context(
            SourceEnum::from($data['source']),
            new CreatorId($data['creatorId'])
        );
    }
}
