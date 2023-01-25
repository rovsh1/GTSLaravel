<?php

namespace GTS\Hotel\Infrastructure\Facade\Reservation;

use GTS\Hotel\Application\Command\ReserveQuota;
use GTS\Hotel\Application\Query\GetActiveReservations;
use GTS\Shared\Application\Command\CommandBusInterface;
use GTS\Shared\Application\Query\QueryBusInterface;

class Api implements ApiInterface
{

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function getActiveReservations()
    {
        return $this->queryBus->execute(new GetActiveReservations());
    }

    public function reserveQuota($roomId, $date)
    {
        return $this->commandBus->execute(new ReserveQuota($roomId, $date));
    }
}
