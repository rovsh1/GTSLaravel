<?php

namespace GTS\Integration\Traveline\Application\Dto;

use GTS\Shared\Application\Dto\Attributes\MapInputName;
use GTS\Shared\Application\Dto\Attributes\MapOutputName;
use GTS\Shared\Application\Dto\Attributes\WithCast;
use GTS\Shared\Application\Dto\Dto;
use GTS\Shared\Application\Dto\DtoCollection;
use GTS\Shared\Application\Dto\DtoCollectionOf;
use GTS\Shared\Application\Dto\Optional;

class RoomDto extends Dto
{
    public function __construct(
        #[MapOutputName('roomTypeId')]
        public readonly int                    $id,
        #[MapOutputName('roomName')]
        public readonly string                 $name,
        /** @var RatePlanDto[] $ratePlans */
        #[DtoCollectionOf(RatePlanDto::class), MapInputName('priceRates')]
        public readonly DtoCollection          $ratePlans,
        /** @var OccupancyDto[] $occupancies */
        #[DtoCollectionOf(OccupancyDto::class), WithCast(OccupancyCast::class), MapInputName('guestsNumber')]
        public readonly DtoCollection|Optional $occupancies
    ) {}
}
