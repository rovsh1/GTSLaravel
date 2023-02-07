<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use Carbon\CarbonInterface;
use Custom\Framework\Bus\CommandBusInterface;
use GTS\Integration\Traveline\Application\Command\ConfirmReservations;
use GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;

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
