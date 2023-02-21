<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class OpenRoomQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    /**
     * @param OpenRoomQuota $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->quotaRepository->openRoomQuota($command->roomId, $command->period);
    }
}
