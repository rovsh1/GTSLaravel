<?php

namespace Module\Reservation\Common\Domain\ValueObject;

class Creator
{
    public function __construct(
        private readonly int $id,
        private readonly CreatorTypeEnum $type,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function type(): CreatorTypeEnum
    {
        return $this->type;
    }
}
