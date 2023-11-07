<?php

namespace Module\Booking\Shared\Domain\Shared\ValueObject;

class Creator
{
    public function __construct(
        private readonly CreatorId $id,
        private readonly CreatorTypeEnum $type,
    ) {}

    public function id(): CreatorId
    {
        return $this->id;
    }

    public function type(): CreatorTypeEnum
    {
        return $this->type;
    }
}
