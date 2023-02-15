<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Hotel\Application\Command\CloseRoomQuota;
use GTS\Hotel\Application\Command\OpenRoomQuota;
use GTS\Hotel\Application\Command\UpdateRoomPrice;
use GTS\Hotel\Application\Command\UpdateRoomQuota;
use GTS\Hotel\Application\Query\GetActiveReservations;

class RoomQuotaFacade implements RoomQuotaFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {}

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
}
