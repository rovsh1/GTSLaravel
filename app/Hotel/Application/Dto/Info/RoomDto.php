<?php

namespace GTS\Hotel\Application\Dto\Info;

use GTS\Shared\Application\Dto\AbstractDto;
use GTS\Shared\Application\Dto\AbstractDtoCollection;
use GTS\Shared\Application\Dto\AbstractDtoCollectionOf;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class RoomDto extends AbstractDto
{
    public function __construct(
        public readonly int                   $id,
        public readonly string                $name,
        #[DataCollectionOf(PriceRateDto::class)]
        public readonly DataCollection $priceRates
    ) {}
}
