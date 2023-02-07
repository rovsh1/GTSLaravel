<?php

namespace GTS\Reservation\HotelReservation\Application\Query;

use Custom\Framework\Bus\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(public readonly int $id) {}
}
