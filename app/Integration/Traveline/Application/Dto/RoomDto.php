<?php

namespace GTS\Integration\Traveline\Application\Dto;

use Custom\Dto\Attributes\MapInputName;
use Custom\Dto\Attributes\MapOutputName;
use Custom\Dto\Attributes\WithCast;
use Custom\Dto\Dto;
use Custom\Dto\DtoCollection;
use Custom\Dto\DtoCollectionOf;
use Custom\Dto\Optional;

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
