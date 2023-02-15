<?php

namespace GTS\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetActiveReservations implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId
    ) {}
}
