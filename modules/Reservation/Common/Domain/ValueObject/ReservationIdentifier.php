<?php

namespace Module\Reservation\Common\Domain\ValueObject;

class ReservationIdentifier
{
    public function __construct(
        private readonly int $id,
        private readonly string $number,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function number(): string
    {
        return $this->number;
    }
}
