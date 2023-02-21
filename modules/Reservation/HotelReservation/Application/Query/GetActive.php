<?php

namespace Module\Reservation\HotelReservation\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetActive implements QueryInterface
{
    public function __construct(
        public readonly ?int $hotelId
    ) {}
}
