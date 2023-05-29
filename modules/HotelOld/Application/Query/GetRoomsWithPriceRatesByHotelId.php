<?php

namespace Module\HotelOld\Application\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRoomsWithPriceRatesByHotelId implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId
    ) {}
}
