<?php

namespace GTS\Services\Integration\Traveline\Application\Dto;

use GTS\Shared\Application\Dto\Dto;
use GTS\Shared\Application\Dto\DtoCollection;
use GTS\Shared\Application\Dto\DtoCollectionOf;

class HotelDto extends Dto
{
    public function __construct(
        public readonly int           $id,
        public readonly string        $name,
        /** @var RoomDto[] $rooms */
        #[DtoCollectionOf(RoomDto::class)]
        public readonly DtoCollection $rooms,
    ) {}
}
