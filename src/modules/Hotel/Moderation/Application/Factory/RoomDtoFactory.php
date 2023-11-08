<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Factory;

use Module\Hotel\Moderation\Application\Dto\PriceRateDto;
use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Hotel\Moderation\Domain\Hotel\Entity\Room;

class RoomDtoFactory
{
    public function fromRoom(Room $entity): RoomDto
    {
        return new RoomDto(
            $entity->id->value(),
            $entity->hotelId->value(),
            $entity->name,
            array_map(fn($r) => new PriceRateDto(
                id: $r->id,
                name: $r->name,
                description: $r->description
            ), $entity->priceRates),
            $entity->guestsCount,
            $entity->roomsCount,
        );
    }

    /**
     * @param Room[] $rooms
     * @return RoomDto[]
     */
    public function collection(array $rooms): array
    {
        return array_map(fn($r) => $this->fromRoom($r), $rooms);
    }
}
