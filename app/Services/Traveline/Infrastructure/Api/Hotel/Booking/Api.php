<?php

namespace GTS\Services\Traveline\Infrastructure\Api\Hotel\Booking;

use GTS\Services\Traveline\Application\Command\GetReservations;
use GTS\Shared\Application\Command\CommandBusInterface;

use Carbon\CarbonInterface;

class Api implements ApiInterface
{

    public function __construct(
        private CommandBusInterface $commandBus
    )
    {
    }

    public function getBookings(?int $hotelId = null, ?CarbonInterface $startDate = null, ?int $bookingId = null)
    {
        return $this->commandBus->execute(new GetReservations($bookingId));
    }
}
