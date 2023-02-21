<?php

namespace Module\Reservation\HotelReservation\Application\Query;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;

class SearchByDateUpdate implements QueryInterface
{
    public function __construct(
        public readonly CarbonInterface $dateUpdate,
        public readonly ?int            $hotelId,
    ) {}
}
