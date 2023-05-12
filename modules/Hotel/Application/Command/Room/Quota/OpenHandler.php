<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class OpenHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|Open $command): void
    {
        $this->quotaRepository->openRoomQuota($command->roomId, $command->period);
    }
}
