<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\ValueObject;

use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\SourceEnum;

class Context
{
    public function __construct(
        private readonly SourceEnum $source,
        private readonly CreatorId $creatorId,
    ) {}

    public function source(): SourceEnum
    {
        return $this->source;
    }

    public function creatorId(): CreatorId
    {
        return $this->creatorId;
    }
}
