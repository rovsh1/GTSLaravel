<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class CloseRoomQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|CloseRoomQuota $command): void
    {
        $this->quotaRepository->closeRoomQuota($command->roomId, $command->period);
    }
}