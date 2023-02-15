<?php

namespace GTS\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class CloseRoomQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    /**
     * @param CloseRoomQuota $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->quotaRepository->closeRoomQuota($command->roomId, $command->period);
    }
}
