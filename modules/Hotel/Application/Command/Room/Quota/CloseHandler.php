<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class CloseHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|Close $command): void
    {
        $this->quotaRepository->closeRoomQuota($command->roomId, $command->period);
    }
}
