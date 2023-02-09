<?php

namespace GTS\Integration\Traveline\Application\Query;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;

class GetReservations implements QueryInterface
{
    public function __construct(
        public readonly ?int             $reservationId,
        public readonly ?int             $hotelId,
        public readonly ?CarbonInterface $dateUpdate,
    ) {}
}
