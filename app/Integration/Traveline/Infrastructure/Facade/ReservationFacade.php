<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\CommandBusInterface;

use GTS\Integration\Traveline\Application\Command\ConfirmReservations;
use GTS\Integration\Traveline\Application\Service\ReservationFinder;

class ReservationFacade implements ReservationFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ReservationFinder   $reservationFinder
    ) {}

    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null): array
    {
        return $this->reservationFinder->getReservations($id, $hotelId, $startDate);
    }

    public function confirmReservations()
    {
        return $this->commandBus->execute(new ConfirmReservations());
    }
}
