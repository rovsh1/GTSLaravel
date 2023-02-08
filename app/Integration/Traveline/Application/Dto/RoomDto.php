<?php

namespace GTS\Integration\Traveline\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Attributes\MapInputName;
use Custom\Framework\Foundation\Support\Dto\Attributes\MapOutputName;
use Custom\Framework\Foundation\Support\Dto\Attributes\WithCast;
use Custom\Framework\Foundation\Support\Dto\Dto;
use Custom\Framework\Foundation\Support\Dto\DtoCollection;
use Custom\Framework\Foundation\Support\Dto\DtoCollectionOf;
use Custom\Framework\Foundation\Support\Dto\Optional;

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
