<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Hotel\Application\Command\UpdateRoomQuota;
use GTS\Hotel\Application\Query\GetActiveReservations;

class ReservationFacade implements ReservationFacadeInterface
{

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface   $queryBus,
    ) {}

    public function getActiveReservations()
    {
        return $this->queryBus->execute(new GetActiveReservations());
    }

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota)
    {
        return $this->commandBus->execute(new UpdateRoomQuota($roomId, $period, $quota));
    }
}
