<?php

namespace Module\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Module\Hotel\Application\Command\CloseRoomQuota;
use Module\Hotel\Application\Command\OpenRoomQuota;
use Module\Hotel\Application\Command\UpdateRoomQuota;

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
