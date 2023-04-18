<?php

namespace Module\Booking\Common\Domain\ValueObject;

class Client
{
    public function __construct(
        private readonly int $id,
        private readonly ClientTypeEnum $type,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function type(): ClientTypeEnum
    {
        return $this->type;
    }
}
