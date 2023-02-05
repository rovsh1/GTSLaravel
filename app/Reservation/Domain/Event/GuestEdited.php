<?php

namespace GTS\Reservation\Domain\Event;

class GuestEdited implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $guestId,
        public readonly string $guestName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
