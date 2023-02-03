<?php

namespace GTS\Services\Traveline\Infrastructure\Facade;

use Carbon\CarbonInterface;
use GTS\Services\Traveline\Application\Command\ConfirmReservations;
use GTS\Services\Traveline\Domain\Adapter\ReservationAdapterInterface;
use GTS\Shared\Application\Command\CommandBusInterface;

class ReservationFacade implements ReservationFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ReservationAdapterInterface $adapter
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
