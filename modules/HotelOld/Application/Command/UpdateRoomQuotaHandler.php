<?php

namespace Module\HotelOld\Application\Command;

use Module\HotelOld\Domain\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class UpdateRoomQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|UpdateRoomQuota $command): void
    {
        $this->quotaRepository->updateRoomQuota($command->roomId, $command->period, $command->quota);
    }
}
