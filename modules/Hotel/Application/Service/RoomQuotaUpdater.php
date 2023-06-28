<?php

namespace Module\Hotel\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Application\Command\Room\Quota\Close;
use Module\Hotel\Application\Command\Room\Quota\Open;
use Module\Hotel\Application\Command\Room\Quota\Reset;
use Module\Hotel\Application\Command\Room\Quota\Update;
use Module\Hotel\Application\Query\FindRoom;
use Module\Hotel\Domain\Exception\Room\RoomNotFound;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\QueryBusInterface;

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
            callback: fn() => $this->commandBus->execute(new Update($roomId, $period, $quota, $releaseDays))
        );
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: fn() => $this->commandBus->execute(new Open($roomId, $period))
        );
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: fn() => $this->commandBus->execute(new Close($roomId, $period))
        );
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->checkRoomExistCallback(
            roomId: $roomId,
            callback: fn() => $this->commandBus->execute(new Reset($roomId, $period))
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
        return $this->queryBus->execute(new FindRoom($roomId)) !== null;
    }

}
