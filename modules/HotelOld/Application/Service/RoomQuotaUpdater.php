<?php

namespace Module\HotelOld\Application\Service;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\HotelOld\Application\Command\CloseRoomQuota;
use Module\HotelOld\Application\Command\OpenRoomQuota;
use Module\HotelOld\Application\Command\UpdateRoomQuota;
use Module\HotelOld\Application\Query\GetRoomById;
use Module\HotelOld\Domain\Exception\Room\RoomNotFound;

class RoomQuotaUpdater
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: fn() => $this->commandBus->execute(new UpdateRoomQuota($roomId, $period, $quota))
        );
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period, int $priceRateId): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: $this->commandBus->execute(new OpenRoomQuota($roomId, $period, $priceRateId))
        );
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period, int $priceRateId): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: $this->commandBus->execute(new CloseRoomQuota($roomId, $period, $priceRateId))
        );
    }

    /**
     * @param int $roomId
     * @param callable $callback
     * @return void
     * @throws RoomNotFound
     */
    private function checkRoomExistCallback(int $roomId, callable $callback): void
    {
        if (!$this->isRoomExist($roomId)) {
            throw new RoomNotFound("Room with id {$roomId} not found");
        }
        $callback();
    }

    private function isRoomExist(int $roomId): bool
    {
        return $this->queryBus->execute(new GetRoomById($roomId)) !== null;
    }

}
