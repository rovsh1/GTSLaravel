<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Reservation;

use Carbon\CarbonInterface;

use GTS\Services\Traveline\Application\Command\ConfirmReservations;
use GTS\Services\Traveline\Domain\Adapter\Reservation\AdapterInterface;
use GTS\Shared\Application\Command\CommandBusInterface;

class Facade implements FacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private AdapterInterface $adapter
    ) {}

    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null)
    {
        $reservationsDto = $this->adapter->getReservations($id, $hotelId, $startDate);

        return $reservationsDto;
    }

    public function confirmReservations()
    {
        return $this->commandBus->execute(new ConfirmReservations());
    }
}
