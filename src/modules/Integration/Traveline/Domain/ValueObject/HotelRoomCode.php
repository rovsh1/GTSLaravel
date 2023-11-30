<?php

namespace Module\Integration\Traveline\Domain\ValueObject;

class HotelRoomCode
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $personQuantity,
    ) {}
}
