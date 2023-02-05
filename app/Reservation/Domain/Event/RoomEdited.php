<?php

namespace GTS\Reservation\Domain\Event;

class RoomEdited implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly string $roomName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
