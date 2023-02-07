<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Custom\Framework\Bus\CommandBusInterface;
use Custom\Framework\Bus\QueryBusInterface;
use GTS\Hotel\Application\Command\ReserveQuota;
use GTS\Hotel\Application\Query\GetActiveReservations;

class ReservationReservationFacade implements ReservationFacadeInterface
{

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {}

    public function getActiveReservations()
    {
        return $this->queryBus->execute(new GetActiveReservations());
    }

    public function reserveQuota($roomId, $date)
    {
        return $this->commandBus->execute(new ReserveQuota($roomId, $date));
    }
}
