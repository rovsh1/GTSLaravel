<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Hotel;

use Carbon\CarbonInterface;

use GTS\Services\Traveline\Application\Command\GetReservations;
use GTS\Services\Traveline\Application\Command\GetRoomsAndRatePlans;
use GTS\Shared\Application\Command\CommandBusInterface;

class Facade implements FacadeInterface
{

    public function __construct(
        private CommandBusInterface $commandBus
    )
    {}

    public function getReservations(?int $reservationId = null, ?int $hotelId = null, ?CarbonInterface $startDate = null)
    {
        return $this->commandBus->execute(new GetReservations($reservationId, $hotelId, $startDate));
    }

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotel = $this->commandBus->execute(new GetRoomsAndRatePlans($hotelId));

        return $hotel; //TODO convert to DTO
    }
}
