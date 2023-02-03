<?php

namespace GTS\Reservation\Domain\Event;

use GTS\Shared\Domain\Event\EventInterface;

class RoomEdited implements EventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly string $roomName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
