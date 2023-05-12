<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class ResetHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|Reset $command): void
    {
        $this->quotaRepository->resetRoomQuota($command->roomId, $command->period);
    }
}
