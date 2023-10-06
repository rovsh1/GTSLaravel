<?php

namespace Module\Booking\Domain\Shared\Entity;

class Client
{
    public function __construct(
        private readonly int $id,
        private readonly ClientTypeEnum $type,
        private readonly string $fullName,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function type(): ClientTypeEnum
    {
        return $this->type;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }
}
