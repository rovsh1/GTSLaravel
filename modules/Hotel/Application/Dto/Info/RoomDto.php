<?php

namespace Module\Hotel\Application\Dto\Info;

use Custom\Framework\Foundation\Support\Dto\Dto;
use Custom\Framework\Foundation\Support\Dto\DtoCollection;
use Custom\Framework\Foundation\Support\Dto\DtoCollectionOf;

class RoomDto extends Dto
{
    public function __construct(
        public readonly int                   $id,
        public readonly string                $name,
        /** @var PriceRateDto[] $priceRates */
        #[DtoCollectionOf(PriceRateDto::class)]
        public readonly DtoCollection $priceRates,
        public readonly int $guestsNumber,
    ) {}
}
