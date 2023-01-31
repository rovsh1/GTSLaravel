<?php

namespace GTS\Hotel\Application\Query;

use GTS\Shared\Application\Query\QueryInterface;

class GetRoomsByHotelId implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId
    ) {}
}
