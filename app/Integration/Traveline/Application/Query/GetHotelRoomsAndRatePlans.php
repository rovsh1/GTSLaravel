<?php

namespace GTS\Integration\Traveline\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetHotelRoomsAndRatePlans implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId,
    ) {}
}
