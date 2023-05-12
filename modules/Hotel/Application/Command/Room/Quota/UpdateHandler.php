<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class UpdateHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|Update $command): void
    {
        $this->quotaRepository->updateRoomQuota($command->roomId, $command->period, $command->quota, $command->releaseDays);
    }
}
