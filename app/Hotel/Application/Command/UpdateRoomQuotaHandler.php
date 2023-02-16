<?php

namespace GTS\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class UpdateRoomQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    /**
     * @param UpdateRoomQuota $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->quotaRepository->updateRoomQuota($command->roomId, $command->period, $command->quota);
    }
}
