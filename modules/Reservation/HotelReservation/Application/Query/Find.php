<?php

namespace Module\Reservation\HotelReservation\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(public readonly int $id) {}
}