<?php

namespace Module\Integration\Traveline\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class HotelRoomCode implements ValueObjectInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $personQuantity,
    ) {}
}
