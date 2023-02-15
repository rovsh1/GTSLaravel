<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use GTS\Hotel\Application\Query\GetActiveReservations;

class ReservationFacade implements ReservationFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface   $queryBus,
    ) {}

    public function getActiveReservations(int $hotelId): array
    {
        return $this->queryBus->execute(new GetActiveReservations($hotelId));
    }
}
