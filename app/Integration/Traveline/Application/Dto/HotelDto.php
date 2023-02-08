<?php

namespace GTS\Integration\Traveline\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Attributes\MapOutputName;
use Custom\Framework\Foundation\Support\Dto\Dto;
use Custom\Framework\Foundation\Support\Dto\DtoCollection;
use Custom\Framework\Foundation\Support\Dto\DtoCollectionOf;
use Custom\Framework\Foundation\Support\Dto\Optional;

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
