<?php

namespace GTS\Reservation\Domain\Event;

use GTS\Shared\Domain\Event\EventInterface;

class GuestEdited implements EventInterface
{
    public function __construct(
        public readonly int $guestId,
        public readonly string $guestName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
