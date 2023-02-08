<?php

namespace GTS\Integration\Traveline\Application\Dto;

use Custom\Dto\Attributes\MapOutputName;
use Custom\Dto\Dto;
use Custom\Dto\DtoCollection;
use Custom\Dto\DtoCollectionOf;
use Custom\Dto\Optional;

class HotelDto extends Dto
{
    public function __construct(
        #[MapOutputName('hotelId')]
        public readonly int                    $id,
        /** @var RoomDto[] $roomsAndRatePlans */
        #[DtoCollectionOf(RoomDto::class)]
        public readonly DtoCollection|Optional $roomsAndRatePlans,
    ) {}
}
