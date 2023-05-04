<?php

namespace Module\Hotel\Application\Service;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\Hotel\Application\Command\CloseRoomQuota;
use Module\Hotel\Application\Command\OpenRoomQuota;
use Module\Hotel\Application\Command\UpdateRoomQuota;
use Module\Hotel\Application\Query\GetRoomById;
use Module\Hotel\Domain\Exception\Room\RoomNotFound;

class RoomQuotaUpdater
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: fn() => $this->commandBus->execute(new UpdateRoomQuota($roomId, $period, $quota, $releaseDays))
        );
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: fn() => $this->commandBus->execute(new OpenRoomQuota($roomId, $period))
        );
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: fn() => $this->commandBus->execute(new CloseRoomQuota($roomId, $period))
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
