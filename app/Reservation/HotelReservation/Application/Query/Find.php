<?php

namespace GTS\Reservation\HotelReservation\Application\Query;

use GTS\Shared\Application\Query\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(public readonly int $id) {}
}
