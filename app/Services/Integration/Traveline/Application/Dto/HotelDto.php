<?php

namespace GTS\Services\Integration\Traveline\Application\Dto;

use GTS\Shared\Application\Dto\Attributes\MapOutputName;
use GTS\Shared\Application\Dto\Dto;
use GTS\Shared\Application\Dto\DtoCollection;
use GTS\Shared\Application\Dto\DtoCollectionOf;
use GTS\Shared\Application\Dto\Optional;

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
