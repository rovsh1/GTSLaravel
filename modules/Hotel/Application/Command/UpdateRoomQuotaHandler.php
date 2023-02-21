<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

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
