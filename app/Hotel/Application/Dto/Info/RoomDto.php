<?php

namespace GTS\Hotel\Application\Dto\Info;

use Custom\Dto\Dto;
use Custom\Dto\DtoCollection;
use Custom\Dto\DtoCollectionOf;

class RoomDto extends Dto
{
    public function __construct(
        public readonly int                   $id,
        public readonly string                $name,
        /** @var PriceRateDto[] $priceRates */
        #[DtoCollectionOf(PriceRateDto::class)]
        public readonly DtoCollection $priceRates,
        #[DtoCollectionOf(PriceRateDto::class)]
        public readonly int $guestsNumber,
    ) {}
}
