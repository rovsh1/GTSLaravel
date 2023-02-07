<?php

namespace GTS\Hotel\Application\Query;

use Custom\Framework\Bus\QueryInterface;

class GetRoomsWithPriceRatesByHotelId implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId
    ) {}
}
