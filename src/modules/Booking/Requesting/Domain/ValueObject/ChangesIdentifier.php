<?php

namespace Module\Booking\Requesting\Domain\ValueObject;

final class ChangesIdentifier
{
    public function __construct(
        private readonly int $bookingId,
        private readonly string $field,
    ) {}

    public function bookingId(): int
    {
        return $this->bookingId;
    }

    public function field(): string
    {
        return $this->field;
    }
}