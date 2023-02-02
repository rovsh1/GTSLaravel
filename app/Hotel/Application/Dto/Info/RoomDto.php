<?php

namespace GTS\Hotel\Application\Dto\Info;

use GTS\Shared\Application\Dto\Dto;
use GTS\Shared\Application\Dto\DtoCollection;
use GTS\Shared\Application\Dto\DtoCollectionOf;

class RoomDto extends Dto
{
    public function __construct(
        public readonly int                   $id,
        public readonly string                $name,
        /** @var PriceRateDto[] $priceRates */
        #[DtoCollectionOf(PriceRateDto::class)]
        public readonly DtoCollection $priceRates
    ) {}
}
