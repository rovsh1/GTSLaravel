<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Hotel\Application\Command\CloseRoomQuota;
use GTS\Hotel\Application\Command\OpenRoomQuota;
use GTS\Hotel\Application\Command\UpdateRoomPriceRate;
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

    public function openRoomQuota(int $roomId, CarbonPeriod $period, int $rateId)
    {
        return $this->commandBus->execute(new OpenRoomQuota($roomId, $period, $rateId));
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period, int $rateId)
    {
        return $this->commandBus->execute(new CloseRoomQuota($roomId, $period, $rateId));
    }

    public function updateRoomPriceRate(int $roomId, CarbonPeriod $period, int $rateId, string $currencyCode)
    {
        return $this->commandBus->execute(new UpdateRoomPriceRate($roomId, $period, $rateId, $currencyCode));
    }
}
