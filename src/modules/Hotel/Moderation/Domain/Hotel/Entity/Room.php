<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Entity;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\RoomId;
use Module\Shared\Contracts\Domain\EntityInterface;

class Room implements EntityInterface
{
    public function __construct(
        public readonly RoomId $id,
        public readonly HotelId $hotelId,
        public readonly string $name,
        /** @var PriceRate[] $priceRates */
        public readonly array $priceRates,
        public readonly int $guestsCount,
        public readonly int $roomsCount,
    ) {}

    public function id(): RoomId
    {
        return $this->id;
    }
}
