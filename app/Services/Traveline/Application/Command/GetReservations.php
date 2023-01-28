<?php

namespace GTS\Services\Traveline\Application\Command;

use Carbon\CarbonInterface;

use GTS\Shared\Application\Command\CommandInterface;

class GetReservations implements CommandInterface
{
    public function __construct(
        public readonly ?int             $reservationId,
        public readonly ?int             $hotelId,
        public readonly ?CarbonInterface $startDate,
    )
    {
    }
}
