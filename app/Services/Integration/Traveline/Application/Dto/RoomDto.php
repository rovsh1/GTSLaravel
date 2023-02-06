<?php

namespace GTS\Services\Integration\Traveline\Application\Dto;

use GTS\Shared\Application\Dto\Attributes\MapInputName;
use GTS\Shared\Application\Dto\Dto;
use GTS\Shared\Application\Dto\DtoCollection;
use GTS\Shared\Application\Dto\DtoCollectionOf;

class RoomDto extends Dto
{
    public function __construct(
        public readonly int           $id,
        public readonly string        $name,
        /** @var RatePlanDto[] $ratePlans */
        #[DtoCollectionOf(RatePlanDto::class), MapInputName('priceRates')]
        public readonly DtoCollection $ratePlans
    ) {}
}
