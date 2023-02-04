<?php

namespace GTS\Reservation\Application\Query\HotelReservation;

use GTS\Shared\Application\Query\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(public readonly int $id) {}
}
