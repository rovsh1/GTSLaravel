<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Reservation;

use Carbon\CarbonInterface;

use GTS\Services\Traveline\Application\Command\ConfirmReservations;
use GTS\Services\Traveline\Application\Command\GetReservations;
use GTS\Shared\Application\Command\CommandBusInterface;

class Facade implements FacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null)
    {
        return $this->commandBus->execute(new GetReservations($id, $hotelId, $startDate));
    }

    public function confirmReservations()
    {
        return $this->commandBus->execute(new ConfirmReservations());
    }
}
