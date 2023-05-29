<?php

namespace Module\HotelOld\Application\Dto\Info;

use Sdk\Module\Foundation\Support\Dto\Dto;
use Sdk\Module\Foundation\Support\Dto\DtoCollection;
use Sdk\Module\Foundation\Support\Dto\DtoCollectionOf;

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
